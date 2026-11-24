<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';
require_once __DIR__ . '/icones.php';

$pagina_atual = 'grupos';
$titulo_topo = 'Grupos';

$id_usuario = (int) $_SESSION['id_usuario'];

$stmt = $pdo->prepare("SELECT g.id_grupo,
                              g.nome_grupo,
                              g.descricao,
                              mu.papel,
                              (SELECT COUNT(*)
                               FROM membro_usuario m
                               WHERE m.id_grupo = g.id_grupo) AS total_membros,
                              (SELECT COUNT(*)
                               FROM desafio d
                               WHERE d.id_grupo = g.id_grupo
                               AND CURDATE() BETWEEN d.data_inicio AND d.data_fim) AS desafios_ativos
                       FROM grupo g
                       INNER JOIN membro_usuario mu
                               ON mu.id_grupo = g.id_grupo
                              AND mu.id_usuario = :id_usuario
                       ORDER BY mu.data_entrada DESC, g.id_grupo DESC");
$stmt->execute([':id_usuario' => $id_usuario]);
$linhas_grupos = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT m.id_grupo, m.id_usuario, m.papel, u.nome
                       FROM membro_usuario m
                       INNER JOIN usuario u ON u.id_usuario = m.id_usuario
                       WHERE m.id_grupo IN (SELECT id_grupo
                                            FROM membro_usuario
                                            WHERE id_usuario = :id_usuario)
                       ORDER BY m.papel = 'Administrador' DESC, m.data_entrada ASC");
$stmt->execute([':id_usuario' => $id_usuario]);

$cores_avatar = ['#005AB5', '#B84700', '#00684A', '#6A359C', '#9E1B5E', '#005F73'];
$medalhas_grupo = [1 => '🥇', 2 => '🥈', 3 => '🥉'];

$membros_por_grupo = [];
$membros_detalhados_por_grupo = [];
foreach ($stmt->fetchAll() as $membro) {
    $membros_por_grupo[$membro['id_grupo']][] = iniciais($membro['nome']);
    $membros_detalhados_por_grupo[$membro['id_grupo']][] = [
        'id'       => (int) $membro['id_usuario'],
        'nome'     => $membro['nome'],
        'papel'    => $membro['papel'],
        'iniciais' => iniciais($membro['nome']),
    ];
}

// RF25 / RF26 / RF27 / RF28 - Buscar todos os desafios internos dos grupos do usuário
$stmt = $pdo->prepare(
    "SELECT d.id_desafio,
            d.id_grupo,
            d.titulo,
            d.descricao,
            d.categoria,
            d.data_inicio,
            d.data_fim,
            d.tipo_meta,
            d.valor_meta,
            (CURDATE() BETWEEN d.data_inicio AND d.data_fim) AS ativo
     FROM desafio d
     WHERE d.id_grupo IN (
         SELECT id_grupo
         FROM membro_usuario
         WHERE id_usuario = :id_usuario
     )
     ORDER BY d.id_grupo, ativo DESC, d.data_fim ASC, d.id_desafio DESC"
);
$stmt->execute([':id_usuario' => $id_usuario]);
$desafios_brutos_por_grupo = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $desafio_bruto) {
    $desafios_brutos_por_grupo[(int) $desafio_bruto['id_grupo']][] = $desafio_bruto;
}

// RF27 / RF28 - Buscar participações e progressos dos integrantes nos desafios de cada grupo
$stmt = $pdo->prepare(
    "SELECT p.id_grupo,
            p.id_desafio,
            p.id_usuario,
            p.progresso_atual,
            p.status
     FROM participacao p
     INNER JOIN desafio d
             ON d.id_desafio = p.id_desafio
            AND d.id_grupo = p.id_grupo
     WHERE p.id_grupo IN (
         SELECT id_grupo
         FROM membro_usuario
         WHERE id_usuario = :id_usuario
     )"
);
$stmt->execute([':id_usuario' => $id_usuario]);
$participacoes_mapa = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $part) {
    $id_g = (int) $part['id_grupo'];
    $id_d = (int) $part['id_desafio'];
    $id_u = (int) $part['id_usuario'];
    $participacoes_mapa[$id_g][$id_d][$id_u] = [
        'progresso_atual' => (int) $part['progresso_atual'],
        'status'          => $part['status'],
    ];
}

$desafios_por_grupo = [];
$grupos = [];

foreach ($linhas_grupos as $linha) {
    $id_grupo = (int) $linha['id_grupo'];
    $membros_detalhados = $membros_detalhados_por_grupo[$id_grupo] ?? [];
    $lista_desafios_brutos = $desafios_brutos_por_grupo[$id_grupo] ?? [];
    $todos_desafios_grupo = [];

    foreach ($lista_desafios_brutos as $desafio) {
        $id_desafio = (int) $desafio['id_desafio'];
        $valor_meta = max(1, (int) $desafio['valor_meta']);
        $mapa_desafio = $participacoes_mapa[$id_grupo][$id_desafio] ?? [];

        // RF27 - Monta o progresso de todos os integrantes do grupo neste desafio
        $progresso_membros = [];
        foreach ($membros_detalhados as $idx_membro => $membro) {
            $id_membro = $membro['id'];
            $sou_eu = ($id_membro === $id_usuario);
            $participa = isset($mapa_desafio[$id_membro]);
            $progresso_atual = $participa ? (int) $mapa_desafio[$id_membro]['progresso_atual'] : 0;
            $status_part = $participa ? $mapa_desafio[$id_membro]['status'] : 'nao_participa';
            $concluido = $participa && ($progresso_atual >= $valor_meta || $status_part === 'concluido');
            $percentual = $participa ? min(100, (int) round(($progresso_atual / $valor_meta) * 100)) : 0;

            $progresso_membros[] = [
                'id'              => $id_membro,
                'nome'            => $membro['nome'],
                'iniciais'        => $membro['iniciais'],
                'cor'             => $sou_eu ? '#B84700' : $cores_avatar[$idx_membro % count($cores_avatar)],
                'papel'           => $membro['papel'],
                'eu'              => $sou_eu,
                'participando'    => $participa,
                'progresso_atual' => $progresso_atual,
                'valor_meta'      => $valor_meta,
                'percentual'      => $percentual,
                'concluido'       => $concluido,
            ];
        }

        usort($progresso_membros, function ($a, $b) {
            if ($b['percentual'] !== $a['percentual']) {
                return $b['percentual'] <=> $a['percentual'];
            }
            if ($b['participando'] !== $a['participando']) {
                return $b['participando'] <=> $a['participando'];
            }
            return strcasecmp($a['nome'], $b['nome']);
        });

        $minha_part = $mapa_desafio[$id_usuario] ?? null;
        $meu_progresso = $minha_part ? (int) $minha_part['progresso_atual'] : 0;
        $meu_percentual = $minha_part ? min(100, (int) round(($meu_progresso / $valor_meta) * 100)) : 0;
        $meu_concluido = $minha_part && ($meu_progresso >= $valor_meta || $minha_part['status'] === 'concluido');

        $desafio_formatado = [
            'id_desafio'          => $id_desafio,
            'id_grupo'            => $id_grupo,
            'titulo'              => $desafio['titulo'],
            'descricao'           => $desafio['descricao'] ?? '',
            'categoria'           => $desafio['categoria'],
            'data_inicio'         => $desafio['data_inicio'],
            'data_fim'            => $desafio['data_fim'],
            'tipo_meta'           => $desafio['tipo_meta'],
            'valor_meta'          => $valor_meta,
            'ativo'               => (bool) $desafio['ativo'],
            'total_participantes' => count($mapa_desafio),
            'participando'        => $minha_part !== null,
            'meu_progresso'       => $meu_progresso,
            'meu_percentual'      => $meu_percentual,
            'meu_concluido'       => $meu_concluido,
            'progresso_membros'   => $progresso_membros,
        ];

        $todos_desafios_grupo[] = $desafio_formatado;
        if ($desafio_formatado['ativo']) {
            $desafios_por_grupo[$id_grupo][] = $desafio_formatado;
        }
    }

    // RF27 (resumo por integrante) e RF28 (ranking próprio de cada grupo, independente do ranking geral)
    $ranking_grupo = [];
    $total_desafios_grupo = count($todos_desafios_grupo);

    foreach ($membros_detalhados as $idx_membro => $membro) {
        $id_membro = $membro['id'];
        $sou_eu = ($id_membro === $id_usuario);
        $pontos_grupo = 0;
        $desafios_participando = 0;
        $desafios_concluidos = 0;
        $soma_percentuais = 0;

        foreach ($todos_desafios_grupo as $desafio_g) {
            $id_d = $desafio_g['id_desafio'];
            $meta_d = $desafio_g['valor_meta'];
            $part_membro = $participacoes_mapa[$id_grupo][$id_d][$id_membro] ?? null;

            if ($part_membro !== null) {
                $desafios_participando++;
                $prog = max(0, (int) $part_membro['progresso_atual']);
                $razao = min(1, $prog / $meta_d);
                $pontos_grupo += (int) round($razao * 100);
                $soma_percentuais += (int) round($razao * 100);

                if ($prog >= $meta_d || $part_membro['status'] === 'concluido') {
                    $desafios_concluidos++;
                }
            }
        }

        $media_progresso = $total_desafios_grupo > 0
            ? (int) round($soma_percentuais / $total_desafios_grupo)
            : 0;

        $ranking_grupo[] = [
            'id'                    => $id_membro,
            'nome'                  => $membro['nome'],
            'iniciais'              => $membro['iniciais'],
            'papel'                 => $membro['papel'],
            'cor'                   => $sou_eu ? '#B84700' : $cores_avatar[$idx_membro % count($cores_avatar)],
            'eu'                    => $sou_eu,
            'pontos'                => $pontos_grupo,
            'desafios_participando' => $desafios_participando,
            'desafios_concluidos'   => $desafios_concluidos,
            'total_desafios'        => $total_desafios_grupo,
            'media_progresso'       => $media_progresso,
        ];
    }

    usort($ranking_grupo, function ($a, $b) {
        if ($b['pontos'] !== $a['pontos']) {
            return $b['pontos'] <=> $a['pontos'];
        }
        if ($b['desafios_concluidos'] !== $a['desafios_concluidos']) {
            return $b['desafios_concluidos'] <=> $a['desafios_concluidos'];
        }
        if ($b['desafios_participando'] !== $a['desafios_participando']) {
            return $b['desafios_participando'] <=> $a['desafios_participando'];
        }
        return strcasecmp($a['nome'], $b['nome']);
    });

    $minha_posicao_grupo = 1;
    $meus_pontos_grupo = 0;
    foreach ($ranking_grupo as $pos_idx => $item_rk) {
        if ($item_rk['eu']) {
            $minha_posicao_grupo = $pos_idx + 1;
            $meus_pontos_grupo = $item_rk['pontos'];
            break;
        }
    }

    $grupos[] = [
        'id'                 => $id_grupo,
        'nome'               => $linha['nome_grupo'],
        'descricao'          => $linha['descricao'] ?? '',
        'admin'              => $linha['papel'] === 'Administrador',
        'total_membros'      => (int) $linha['total_membros'],
        'membros'            => array_slice($membros_por_grupo[$id_grupo] ?? [], 0, 6),
        'membros_detalhados' => $membros_detalhados,
        'total_admins'       => count(array_filter($membros_detalhados, function ($membro) {
            return $membro['papel'] === 'Administrador';
        })),
        'desafios_ativos'    => (int) $linha['desafios_ativos'],
        'todos_desafios'     => $todos_desafios_grupo,
        'ranking'            => $ranking_grupo,
        'minha_posicao'      => $minha_posicao_grupo,
        'meus_pontos'        => $meus_pontos_grupo,
    ];
}

// RF20 - Buscar solicitações pendentes dos grupos administrados

$stmt = $pdo->prepare("
    SELECT s.id_solicitacao,
           s.id_grupo,
           s.id_usuario,
           u.nome,
           s.data_solicitacao
    FROM solicitacao_ingresso_grupo s
    INNER JOIN usuario u
        ON u.id_usuario = s.id_usuario
    INNER JOIN membro_usuario admin
        ON admin.id_grupo = s.id_grupo
       AND admin.id_usuario = :id_admin
       AND admin.papel = 'Administrador'
    WHERE s.status = 'pendente'
    ORDER BY s.data_solicitacao ASC
");

$stmt->execute([':id_admin' => $id_usuario]);

$solicitacoes_por_grupo = [];

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $solicitacao) {
    $solicitacoes_por_grupo[$solicitacao['id_grupo']][] = $solicitacao;
}

$mensagens = [
    'criado'             => ['sucesso', 'Grupo criado! Você é o administrador dele.'],
    'editado'            => ['sucesso', 'Grupo atualizado.'],
    'membro_removido'    => ['sucesso', 'Membro removido do grupo.'],
    'membro_promovido'   => ['sucesso', 'Membro promovido a administrador.'],
    'participando'       => ['sucesso', 'Você entrou no desafio!'],
    'desafio_criado'     => ['sucesso', 'Desafio interno criado com sucesso!'],
    'progresso_salvo'    => ['sucesso', 'Seu progresso no desafio do grupo foi atualizado!'],
];

$erros = [
    'dados'                  => 'Preencha o nome (até 100 caracteres) e a descrição (até 500 caracteres).',
    'nome'                   => 'Já existe um grupo com esse nome. Escolha outro.',
    'permissao'              => 'Só o administrador pode editar este grupo.',
    'permissao_membros'      => 'Só o administrador deste grupo pode gerenciar os membros.',
    'membro_invalido'        => 'Os dados da operação de membros são inválidos.',
    'membro_operacao'        => 'A operação não foi realizada. Verifique se o membro pertence ao grupo e se a ação é permitida.',
    'participacao_dados'     => 'Os dados do desafio são inválidos.',
    'participacao_grupo'     => 'Você não participa deste grupo.',
    'participacao_desafio'   => 'O desafio não está ativo ou não pertence a este grupo.',
    'progresso_dados'        => 'Informe um valor de progresso válido.',
    'progresso_nao_participa'=> 'Participe do desafio antes de atualizar o progresso.',
    'desafio_dados'          => 'Confira os dados do desafio interno e tente novamente.',
    'permissao_desafio'      => 'Só o administrador deste grupo pode criar desafios internos.',
    'salvar'                 => 'Não foi possível salvar o grupo. Tente de novo.',
];

$aviso = null;
foreach ($mensagens as $chave => $mensagem) {
    if (isset($_GET[$chave])) {
        $aviso = $mensagem;
    }
}
if (isset($_GET['erro'])) {
    $aviso = ['erro', $erros[$_GET['erro']] ?? $erros['salvar']];
}

// RF23 - Grupos disponíveis para solicitar ingresso

$stmt = $pdo->prepare("
    SELECT g.id_grupo,
           g.nome_grupo,
           g.descricao,
           (SELECT COUNT(*)
            FROM membro_usuario m
            WHERE m.id_grupo = g.id_grupo) AS total_membros,
           (SELECT s.status
            FROM solicitacao_ingresso_grupo s
            WHERE s.id_grupo = g.id_grupo
              AND s.id_usuario = :id_usuario_solicitacao
            ORDER BY s.id_solicitacao DESC
            LIMIT 1) AS status_solicitacao
    FROM grupo g
    WHERE NOT EXISTS (
        SELECT 1
        FROM membro_usuario m
        WHERE m.id_grupo = g.id_grupo
          AND m.id_usuario = :id_usuario_membro
    )
    ORDER BY g.nome_grupo
");

$stmt->execute([
    ':id_usuario_solicitacao' => $id_usuario,
    ':id_usuario_membro' => $id_usuario
]);

$grupos_disponiveis = $stmt->fetchAll();

// RF24 - Convites recebidos pelo usuário

$stmt = $pdo->prepare("
    SELECT c.id_convite,
           c.id_grupo,
           g.nome_grupo,
           g.descricao,
           u.nome AS nome_remetente,
           c.data_convite
    FROM convite_grupo c
    INNER JOIN grupo g ON g.id_grupo = c.id_grupo
    INNER JOIN usuario u ON u.id_usuario = c.id_usuario_remetente
    WHERE c.id_usuario_convidado = :id_usuario
      AND c.status = 'pendente'
    ORDER BY c.data_convite DESC
");

$stmt->execute([':id_usuario' => $id_usuario]);

$convites_recebidos = $stmt->fetchAll();

// RF24 - Usuários disponíveis para receber convites

$stmt = $pdo->prepare("
    SELECT id_usuario, nome
    FROM usuario
    WHERE id_usuario <> :id_usuario
    ORDER BY nome
");

$stmt->execute([':id_usuario' => $id_usuario]);

$usuarios_para_convidar = $stmt->fetchAll();