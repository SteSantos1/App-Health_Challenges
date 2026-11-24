<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

$resposta_json = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

function responder_participacao($resposta_json, $sucesso, $mensagem, $codigo, $extra = [], $status_http = 200)
{
    if ($resposta_json) {
        http_response_code($status_http);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['sucesso' => $sucesso, 'mensagem' => $mensagem] + $extra, JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($codigo === 'participando') {
        header('Location: ../html/desafios.php?participando=1');
    } elseif ($sucesso) {
        header('Location: ../html/desafios.php');
    } else {
        header('Location: ../html/desafios.php?erro_participar=1');
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/desafios.php');
    exit;
}

$id_desafio = (int) ($_POST['id_desafio'] ?? 0);
$id_usuario = $_SESSION['id_usuario'];

// o desafio existe, eh global e esta ativo?
$sql = "SELECT id_desafio
        FROM desafio
        WHERE id_desafio = :id_desafio
          AND id_grupo IS NULL
          AND CURDATE() BETWEEN data_inicio AND data_fim";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_desafio' => $id_desafio]);

if (!$stmt->fetch()) {
    responder_participacao($resposta_json, false, 'Este desafio não está disponível.', 'indisponivel', [], 404);
}

//  o usuario ja participa?
$sql = "SELECT id_participacao
        FROM participacao
        WHERE id_usuario = :id_usuario
          AND id_desafio = :id_desafio
          AND id_grupo IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':id_desafio' => $id_desafio,
]);

$ja_participa = (bool) $stmt->fetch();

// pra inscrever o usuario no desafio
if (!$ja_participa) {
    $sql = "INSERT INTO participacao (progresso_atual, status, id_usuario, id_grupo, id_desafio)
            VALUES (0, 'ativo', :id_usuario, NULL, :id_desafio)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_desafio' => $id_desafio,
    ]);
}

$sql = "SELECT COUNT(*)
        FROM participacao
        WHERE id_desafio = :id_desafio
          AND id_grupo IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_desafio' => $id_desafio]);
$participantes = (int) $stmt->fetchColumn();

responder_participacao(
    $resposta_json,
    true,
    $ja_participa ? 'Você já participa deste desafio.' : 'Você entrou no desafio!',
    $ja_participa ? 'ja' : 'participando',
    ['participantes' => $participantes, 'novo' => !$ja_participa]
);
