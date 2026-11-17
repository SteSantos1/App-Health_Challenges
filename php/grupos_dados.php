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
                                WHERE CURDATE() BETWEEN d.data_inicio AND d.data_fim) AS desafios_ativos
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

$membros_por_grupo = [];
$membros_detalhados_por_grupo = [];
foreach ($stmt->fetchAll() as $membro) {
    $membros_por_grupo[$membro['id_grupo']][] = iniciais($membro['nome']);
    $membros_detalhados_por_grupo[$membro['id_grupo']][] = [
        'id'    => (int) $membro['id_usuario'],
        'nome'  => $membro['nome'],
        'papel' => $membro['papel'],
    ];
}

$grupos = [];
foreach ($linhas_grupos as $linha) {
    $membros_detalhados = $membros_detalhados_por_grupo[$linha['id_grupo']] ?? [];
    $grupos[] = [
        'id'                 => (int) $linha['id_grupo'],
        'nome'               => $linha['nome_grupo'],
        'descricao'          => $linha['descricao'] ?? '',
        'admin'              => $linha['papel'] === 'Administrador',
        'total_membros'      => (int) $linha['total_membros'],
        'membros'            => array_slice($membros_por_grupo[$linha['id_grupo']] ?? [], 0, 6),
        'membros_detalhados' => $membros_detalhados,
        'total_admins'       => count(array_filter($membros_detalhados, function ($membro) {
            return $membro['papel'] === 'Administrador';
        })),
        'desafios_ativos'    => (int) $linha['desafios_ativos'],
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
    'membro_removido'     => ['sucesso', 'Membro removido do grupo.'],
    'membro_promovido'    => ['sucesso', 'Membro promovido a administrador.'],
];

$erros = [
    'dados'             => 'Preencha o nome (até 100 caracteres) e a descrição (até 500 caracteres).',
    'nome'              => 'Já existe um grupo com esse nome. Escolha outro.',
    'permissao'         => 'Só o administrador pode editar este grupo.',
    'permissao_membros' => 'Só o administrador deste grupo pode gerenciar os membros.',
    'membro_invalido'   => 'Os dados da operação de membros são inválidos.',
    'membro_operacao'   => 'A operação não foi realizada. Verifique se o membro pertence ao grupo e se a ação é permitida.',
    'salvar'            => 'Não foi possível salvar o grupo. Tente de novo.',
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

$cores_avatar = ['#00BC7D', '#2B7FFF', '#F6339A', '#FE9A00', '#8E51FF', '#00B8DB'];

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