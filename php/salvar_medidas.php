<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/condicionamento.php');
    exit;
}

$peso = (float) str_replace(',', '.', $_POST['peso'] ?? 0);
$altura = (float) str_replace(',', '.', $_POST['altura'] ?? 0);

if ($peso < 20 || $peso > 400 || $altura < 0.5 || $altura > 2.5) {
    header('Location: ../html/condicionamento.php?erro_medidas=1');
    exit;
}

$imc = $peso / ($altura * $altura);

$sql = "UPDATE usuario SET peso = :peso, altura = :altura WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':peso'   => $peso,
    ':altura' => $altura,
    ':id'     => $_SESSION['id_usuario'],
]);

$sql = "INSERT INTO historico_corporal (altura, peso, imc, data_registro, id_usuario)
        VALUES (:altura, :peso, :imc, CURDATE(), :id)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':altura' => $altura,
    ':peso'   => $peso,
    ':imc'    => round($imc, 2),
    ':id'     => $_SESSION['id_usuario'],
]);

header('Location: ../html/condicionamento.php?medidas=1');
exit;