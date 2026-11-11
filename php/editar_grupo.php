<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_grupo = (int) ($_POST['id_grupo'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

$stmt = $pdo->prepare("SELECT id_grupo
                       FROM membro_usuario
                       WHERE id_grupo = :id_grupo
                         AND id_usuario = :id_usuario
                         AND papel = 'Administrador'");
$stmt->execute([
    ':id_grupo'   => $id_grupo,
    ':id_usuario' => $id_usuario,
]);

if (!$stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=permissao');
    exit;
}

if ($nome === '' || mb_strlen($nome) > 100 || $descricao === '' || mb_strlen($descricao) > 500) {
    header('Location: ../html/grupos.php?erro=dados');
    exit;
}

$stmt = $pdo->prepare("SELECT id_grupo
                       FROM grupo
                       WHERE nome_grupo = :nome
                         AND id_grupo <> :id_grupo");
$stmt->execute([
    ':nome'     => $nome,
    ':id_grupo' => $id_grupo,
]);

if ($stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=nome');
    exit;
}

$stmt = $pdo->prepare("UPDATE grupo
                       SET nome_grupo = :nome,
                           descricao = :descricao
                       WHERE id_grupo = :id_grupo");
$stmt->execute([
    ':nome'      => $nome,
    ':descricao' => $descricao,
    ':id_grupo'  => $id_grupo,
]);

header('Location: ../html/grupos.php?editado=1');
exit;
