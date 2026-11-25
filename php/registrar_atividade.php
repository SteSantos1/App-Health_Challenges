<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';
require_once __DIR__ . '/tipos_atividade.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/condicionamento.php');
    exit;
}

$tipos = tipos_atividade();

$tipo = $_POST['tipo'] ?? '';
$valor = (int) ($_POST['valor'] ?? 0);
$unidade = $_POST['unidade'] ?? '';
$data_hora = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['data_hora'] ?? '');

if (
    !isset($tipos[$tipo]) ||
    !isset($tipos[$tipo]['unidades'][$unidade]) ||
    $valor <= 0 ||
    !$data_hora ||
    $data_hora > new DateTime()
) {
    header('Location: ../html/condicionamento.php?erro=1');
    exit;
}

$sql = "INSERT INTO atividade (tipo_atividade, valor_atingido, unidade, data_hora, id_usuario)
        VALUES (:tipo, :valor, :unidade, :data_hora, :id_usuario)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':tipo'       => $tipo,
    ':valor'      => $valor,
    ':unidade'    => $unidade,
    ':data_hora'  => $data_hora->format('Y-m-d H:i:s'),
    ':id_usuario' => $_SESSION['id_usuario'],
]);

header('Location: ../html/condicionamento.php?registrado=1');
exit;