<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';
require_once __DIR__ . '/icones.php';

$pagina_atual = 'grupos';
$titulo_topo = 'Grupos';

$id_usuario = $_SESSION['id_usuario'];

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

$stmt = $pdo->prepare("SELECT m.id_grupo, u.nome
                       FROM membro_usuario m
                       INNER JOIN usuario u ON u.id_usuario = m.id_usuario
                       WHERE m.id_grupo IN (SELECT id_grupo
                                            FROM membro_usuario
                                            WHERE id_usuario = :id_usuario)
                       ORDER BY m.papel = 'Administrador' DESC, m.data_entrada ASC");
$stmt->execute([':id_usuario' => $id_usuario]);

$membros_por_grupo = [];
foreach ($stmt->fetchAll() as $membro) {
    $membros_por_grupo[$membro['id_grupo']][] = iniciais($membro['nome']);
}

$grupos = [];
foreach ($linhas_grupos as $linha) {
    $grupos[] = [
        'id'              => (int) $linha['id_grupo'],
        'nome'            => $linha['nome_grupo'],
        'descricao'       => $linha['descricao'] ?? '',
        'admin'           => $linha['papel'] === 'Administrador',
        'total_membros'   => (int) $linha['total_membros'],
        'membros'         => array_slice($membros_por_grupo[$linha['id_grupo']] ?? [], 0, 6),
        'desafios_ativos' => (int) $linha['desafios_ativos'],
    ];
}

$mensagens = [
    'criado'  => ['sucesso', 'Grupo criado! Você é o administrador dele.'],
    'editado' => ['sucesso', 'Grupo atualizado.'],
];

$erros = [
    'dados'     => 'Preencha o nome (até 100 caracteres) e a descrição (até 500 caracteres).',
    'nome'      => 'Já existe um grupo com esse nome. Escolha outro.',
    'permissao' => 'Só o administrador pode editar este grupo.',
    'salvar'    => 'Não foi possível salvar o grupo. Tente de novo.',
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
