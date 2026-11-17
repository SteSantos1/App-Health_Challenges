<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_grupo = filter_input(INPUT_POST, 'id_grupo', FILTER_VALIDATE_INT);
$titulo = trim($_POST['titulo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$categoria = $_POST['categoria'] ?? '';
$data_inicio = $_POST['data_inicio'] ?? '';
$data_fim = $_POST['data_fim'] ?? '';
$tipo_meta = trim($_POST['tipo_meta'] ?? '');
$valor_meta = filter_input(INPUT_POST, 'valor_meta', FILTER_VALIDATE_INT);

if (!$id_grupo
    || $titulo === ''
    || mb_strlen($titulo) > 100
    || mb_strlen($descricao) > 500
    || !in_array($categoria, ['fisica', 'leitura', 'alimento'], true)
    || $valor_meta === false
    || $valor_meta < 1
    || $tipo_meta === ''
    || $data_inicio === ''
    || $data_fim === ''
    || $data_inicio > $data_fim) {
    header('Location: ../html/grupos.php?erro=desafio_dados');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id_grupo
     FROM membro_usuario
     WHERE id_grupo = :id_grupo
       AND id_usuario = :id_usuario
       AND papel = 'Administrador'"
);
$stmt->execute([
    ':id_grupo' => $id_grupo,
    ':id_usuario' => $id_usuario,
]);

if (!$stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=permissao_desafio');
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO desafio
        (titulo, descricao, categoria, data_inicio, data_fim,
         tipo_meta, valor_meta, id_grupo)
     VALUES
        (:titulo, :descricao, :categoria, :data_inicio, :data_fim,
         :tipo_meta, :valor_meta, :id_grupo)"
);
$stmt->execute([
    ':titulo' => $titulo,
    ':descricao' => $descricao,
    ':categoria' => $categoria,
    ':data_inicio' => $data_inicio,
    ':data_fim' => $data_fim,
    ':tipo_meta' => $tipo_meta,
    ':valor_meta' => $valor_meta,
    ':id_grupo' => $id_grupo,
]);

header('Location: ../html/grupos.php?desafio_criado=1');
exit;