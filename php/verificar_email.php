<?php
require_once __DIR__ . '/conexao.php';

header('Content-Type: application/json; charset=utf-8');

$email = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'disponivel' => false,
        'mensagem' => 'Informe um e-mail válido.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$stmt = $pdo->prepare(
    'SELECT 1 FROM usuario WHERE email = :email LIMIT 1'
);
$stmt->execute([':email' => $email]);

$existe = (bool) $stmt->fetchColumn();

echo json_encode([
    'disponivel' => !$existe,
    'mensagem' => $existe
        ? 'Este e-mail já está em uso.'
        : 'E-mail disponível.',
], JSON_UNESCAPED_UNICODE);