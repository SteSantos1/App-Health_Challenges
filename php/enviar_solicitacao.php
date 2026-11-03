<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

$resposta_json = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

function responder_solicitacao($resposta_json, $sucesso, $mensagem, $codigo, $status_http = 200)
{
    if ($resposta_json) {
        http_response_code($status_http);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'sucesso' => $sucesso,
            'mensagem' => $mensagem,
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $parametro = $sucesso ? 'solicitacao=enviada' : 'erro=' . urlencode($codigo);
    header('Location: ../html/amigos.php?' . $parametro);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/amigos.php');
    exit;
}

$id_remetente = (int) $_SESSION['id_usuario'];
$id_destinatario = filter_input(
    INPUT_POST,
    'id_destinatario',
    FILTER_VALIDATE_INT
);
if ($id_destinatario === false || $id_destinatario === null || $id_destinatario < 1) {
    responder_solicitacao($resposta_json, false, 'O ID do usuário não é válido.', 'id', 400);
}

$stmt = $pdo->prepare(
    "SELECT id_usuario
     FROM usuario
     WHERE id_usuario = :id_destinatario"
);

$stmt->execute([
    ':id_destinatario' => $id_destinatario,
]);

if (!$stmt->fetch()) {
    responder_solicitacao($resposta_json, false, 'Esse usuário não existe.', 'usuario', 404);
}

if ($id_destinatario === $id_remetente) {
    responder_solicitacao($resposta_json, false, 'Você não pode enviar uma solicitação para si mesmo.', 'remetente-e-destinatario', 400);
}

$stmt = $pdo->prepare(
    "SELECT status
     FROM amizade
     WHERE (id_usuario_remetente = :rem1 AND id_usuario_destinatario = :dest1)
        OR (id_usuario_remetente = :rem2 AND id_usuario_destinatario = :dest2)
     LIMIT 1"
);

$stmt->execute([
    ':rem1'  => $id_remetente,     // eu mandei
    ':dest1' => $id_destinatario,  // pra ela
    ':rem2'  => $id_destinatario,  // ela mandou
    ':dest2' => $id_remetente,     // pra mim
]);

$registro = $stmt->fetch(PDO::FETCH_ASSOC);

if ($registro) {
    responder_solicitacao($resposta_json, false, 'Já existe uma solicitação ou amizade entre vocês.', 'ja-solicitado', 409);
}

$stmt = $pdo->prepare (
    "INSERT INTO amizade 
    (id_usuario_remetente, id_usuario_destinatario, status, data_conexao)
    VALUES (:id_remetente, :id_destinatario, 'pendente', NOW())"
);

$stmt->execute([
    ':id_remetente' => $id_remetente,
    ':id_destinatario' => $id_destinatario
]);

responder_solicitacao($resposta_json, true, 'Solicitação enviada com sucesso.', 'enviada');
