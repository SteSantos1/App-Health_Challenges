<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/email_codigo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['verificar_email_id'])) {
    header('Location: ../html/index.php');
    exit;
}

$id_usuario = (int) $_SESSION['verificar_email_id'];

$stmt = $pdo->prepare('SELECT nome, email, email_verificado FROM usuario WHERE id_usuario = :id');
$stmt->execute([':id' => $id_usuario]);
$usuario = $stmt->fetch();

if (!$usuario || (int) $usuario['email_verificado'] === 1) {
    unset($_SESSION['verificar_email_id']);
    header('Location: ../html/index.php');
    exit;
}

if (segundos_para_reenviar($pdo, $id_usuario) > 0) {
    header('Location: ../html/confirmar_email.php?erro=aguarde', true, 303);
    exit;
}

$enviado = enviar_novo_codigo($pdo, $id_usuario, $usuario['email'], $usuario['nome']);

header('Location: ../html/confirmar_email.php?' . ($enviado ? 'reenviado=1' : 'falha_envio=1'), true, 303);
exit;
