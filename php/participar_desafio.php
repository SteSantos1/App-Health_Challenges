<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

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
    header('Location: ../html/desafios.php?erro_participar=1');
    exit;
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

if ($stmt->fetch()) {
    header('Location: ../html/desafios.php');
    exit;
}

// pra inscrever o usuario no desafio
$sql = "INSERT INTO participacao (progresso_atual, status, id_usuario, id_grupo, id_desafio)
        VALUES (0, 'ativo', :id_usuario, NULL, :id_desafio)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':id_desafio' => $id_desafio,
]);

header('Location: ../html/desafios.php?participando=1');
exit;