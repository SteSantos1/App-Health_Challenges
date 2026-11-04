<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/inicio.php');
    exit;
}

$id_desafio = (int) ($_POST['id_desafio_diario'] ?? 0);

$pdo->exec("CREATE TABLE IF NOT EXISTS desafio_escolhido (
                id_usuario INT NOT NULL,
                data_escolha DATE NOT NULL,
                id_desafio_diario INT NOT NULL,
                PRIMARY KEY (id_usuario, data_escolha)
            )");

// Só aceita desafios sorteados para o dia de hoje
$stmt = $pdo->prepare("SELECT id_desafio_diario FROM desafio_diario
                       WHERE id_desafio_diario = :id AND data_sorteio = CURDATE()");
$stmt->execute([':id' => $id_desafio]);

if (!$stmt->fetch()) {
    header('Location: ../html/inicio.php?erro=desafio');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO desafio_escolhido (id_usuario, data_escolha, id_desafio_diario)
                       VALUES (:usuario, CURDATE(), :id)
                       ON DUPLICATE KEY UPDATE id_desafio_diario = VALUES(id_desafio_diario)");
$stmt->execute([
    ':usuario' => $_SESSION['id_usuario'],
    ':id'      => $id_desafio,
]);

header('Location: ../html/inicio.php');
exit;
