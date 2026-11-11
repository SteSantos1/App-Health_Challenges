<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

if ($nome === '' || mb_strlen($nome) > 100 || $descricao === '' || mb_strlen($descricao) > 500) {
    header('Location: ../html/grupos.php?erro=dados');
    exit;
}

$stmt = $pdo->prepare("SELECT id_grupo FROM grupo WHERE nome_grupo = :nome");
$stmt->execute([':nome' => $nome]);

if ($stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=nome');
    exit;
}

$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("INSERT INTO grupo (nome_grupo, descricao, id_criador)
                           VALUES (:nome, :descricao, :id_usuario)");
    $stmt->execute([
        ':nome'       => $nome,
        ':descricao'  => $descricao,
        ':id_usuario' => $id_usuario,
    ]);

    $id_grupo = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO membro_usuario (id_usuario, id_grupo, papel, data_entrada)
                           VALUES (:id_usuario, :id_grupo, 'Administrador', CURDATE())");
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_grupo'   => $id_grupo,
    ]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    header('Location: ../html/grupos.php?erro=salvar');
    exit;
}

header('Location: ../html/grupos.php?criado=1');
exit;
