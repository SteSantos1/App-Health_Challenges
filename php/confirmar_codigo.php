<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/email_codigo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['verificar_email_id'])) {
    header('Location: ../html/index.php');
    exit;
}

$id_usuario = (int) $_SESSION['verificar_email_id'];
$codigo_digitado = preg_replace('/\D/', '', $_POST['codigo'] ?? '');

function voltar_confirmacao(string $erro): void
{
    header('Location: ../html/confirmar_email.php?erro=' . $erro, true, 303);
    exit;
}

$stmt = $pdo->prepare(
    'SELECT codigo_hash, tentativas, expira_em > NOW() AS valido
     FROM verificacao_email WHERE id_usuario = :id'
);
$stmt->execute([':id' => $id_usuario]);
$registro = $stmt->fetch();

if (!$registro) {
    voltar_confirmacao('sem-codigo');
}

if (!(int) $registro['valido']) {
    voltar_confirmacao('expirado');
}

if ((int) $registro['tentativas'] >= CODIGO_MAX_TENTATIVAS) {
    voltar_confirmacao('tentativas');
}

if (strlen($codigo_digitado) !== 6) {
    voltar_confirmacao('formato');
}

if (!password_verify($codigo_digitado, $registro['codigo_hash'])) {
    $pdo->prepare('UPDATE verificacao_email SET tentativas = tentativas + 1 WHERE id_usuario = :id')
        ->execute([':id' => $id_usuario]);
    voltar_confirmacao('incorreto');
}

$stmt = $pdo->prepare('SELECT nome FROM usuario WHERE id_usuario = :id');
$stmt->execute([':id' => $id_usuario]);
$nome = $stmt->fetchColumn();

try {
    $pdo->beginTransaction();
    $pdo->prepare('UPDATE usuario SET email_verificado = 1 WHERE id_usuario = :id')
        ->execute([':id' => $id_usuario]);
    $pdo->prepare('DELETE FROM verificacao_email WHERE id_usuario = :id')
        ->execute([':id' => $id_usuario]);
    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());
    voltar_confirmacao('falha');
}

unset($_SESSION['verificar_email_id']);
session_regenerate_id(true);
$_SESSION['id_usuario'] = $id_usuario;
$_SESSION['nome'] = $nome;

header('Location: ../html/inicio.php');
exit;
