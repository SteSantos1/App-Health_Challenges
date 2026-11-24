<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';
require_once __DIR__ . '/conquistas.php';
require_once __DIR__ . '/pontos.php';

$resposta_json = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

function responder_conclusao($resposta_json, $sucesso, $mensagem, $codigo, $extra = [], $status_http = 200)
{
    if ($resposta_json) {
        http_response_code($status_http);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['sucesso' => $sucesso, 'mensagem' => $mensagem] + $extra, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $destinos = [
        'quantidade'  => '?erro=quantidade',
        'sem_desafio' => '?erro=sem_desafio',
        'meta'        => '?erro=meta',
        'ja'          => '?concluido=ja',
        'concluido'   => '?concluido=1',
    ];
    header('Location: ../html/inicio.php' . ($destinos[$codigo] ?? ''));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/inicio.php');
    exit;
}

$quantidade_feita = filter_input(INPUT_POST, 'quantidade_feita', FILTER_VALIDATE_INT);

if ($quantidade_feita === false || $quantidade_feita === null || $quantidade_feita < 1) {
    responder_conclusao($resposta_json, false, 'Informe uma quantidade válida.', 'quantidade', [], 400);
}

$stmt = $pdo->prepare(
    "SELECT dd.id_desafio_diario, dd.quantidade_exigida, dd.pontos_recompensa
     FROM desafio_escolhido AS de
     INNER JOIN desafio_diario AS dd
             ON dd.id_desafio_diario = de.id_desafio_diario
     WHERE de.id_usuario = :id_usuario
       AND de.data_escolha = CURDATE()
       AND dd.data_sorteio = CURDATE()
     LIMIT 1"
);
$stmt->execute([':id_usuario' => $_SESSION['id_usuario']]);
$desafio = $stmt->fetch();

if (!$desafio) {
    responder_conclusao($resposta_json, false, 'Escolha um desafio de hoje antes de concluir.', 'sem_desafio', [], 400);
}

if ($quantidade_feita < (int) $desafio['quantidade_exigida']) {
    responder_conclusao($resposta_json, false, 'Você ainda não atingiu a meta do desafio.', 'meta', [], 400);
}

$stmt = $pdo->prepare(
    "SELECT id_registro
     FROM registro_conclusao
     WHERE id_usuario = :id_usuario
       AND id_desafio_diario = :id_desafio_diario
       AND data_hora >= CURDATE()
       AND data_hora < CURDATE() + INTERVAL 1 DAY
     LIMIT 1"
);
$stmt->execute([
    ':id_usuario' => $_SESSION['id_usuario'],
    ':id_desafio_diario' => $desafio['id_desafio_diario'],
]);

if ($stmt->fetch()) {
    responder_conclusao(
        $resposta_json,
        false,
        'Este desafio já foi registrado como concluído.',
        'ja',
        ['concluido' => true],
        409
    );
}

$stmt = $pdo->prepare(
    "INSERT INTO registro_conclusao
        (quantidade_feita, pontos_ganhos, data_hora, status_conclusao, id_usuario, id_desafio_diario)
     VALUES
        (:quantidade_feita, :pontos_ganhos, NOW(), 1, :id_usuario, :id_desafio_diario)"
);
$stmt->execute([
    ':quantidade_feita' => $quantidade_feita,
    ':pontos_ganhos' => $desafio['pontos_recompensa'],
    ':id_usuario' => $_SESSION['id_usuario'],
    ':id_desafio_diario' => $desafio['id_desafio_diario'],
]);

desbloquear_conquistas_por_pontuacao($pdo, (int) $_SESSION['id_usuario']);

$conquistas = obter_conquistas_usuario($pdo, (int) $_SESSION['id_usuario']);
$desbloqueadas = [];
foreach ($conquistas as $conquista) {
    if ($conquista['desbloqueada']) {
        $desbloqueadas[] = $conquista['chave'];
    }
}

responder_conclusao(
    $resposta_json,
    true,
    'Desafio concluído! Você ganhou ' . (int) $desafio['pontos_recompensa'] . ' pontos.',
    'concluido',
    [
        'pontos_ganhos' => (int) $desafio['pontos_recompensa'],
        'pontos_mes' => pontos_do_mes($pdo, (int) $_SESSION['id_usuario']),
        'conquistas' => $desbloqueadas,
    ]
);
