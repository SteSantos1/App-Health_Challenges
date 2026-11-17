<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_grupo = filter_input(INPUT_POST, 'id_grupo', FILTER_VALIDATE_INT);
$id_desafio = filter_input(INPUT_POST, 'id_desafio', FILTER_VALIDATE_INT);

if ($id_grupo === false || $id_grupo === null || $id_grupo < 1
    || $id_desafio === false || $id_desafio === null || $id_desafio < 1) {
    header('Location: ../html/grupos.php?erro=participacao_dados');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id_grupo
     FROM membro_usuario
     WHERE id_grupo = :id_grupo
       AND id_usuario = :id_usuario"
);
$stmt->execute([
    ':id_grupo' => $id_grupo,
    ':id_usuario' => $id_usuario,
]);

if (!$stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=participacao_grupo');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id_desafio
     FROM desafio
     WHERE id_desafio = :id_desafio
       AND id_grupo = :id_grupo
       AND CURDATE() BETWEEN data_inicio AND data_fim"
);
$stmt->execute([
    ':id_desafio' => $id_desafio,
    ':id_grupo' => $id_grupo,
]);

if (!$stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=participacao_desafio');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id_participacao
     FROM participacao
     WHERE id_usuario = :id_usuario
       AND id_grupo = :id_grupo
       AND id_desafio = :id_desafio"
);
$stmt->execute([
    ':id_usuario' => $id_usuario,
    ':id_grupo' => $id_grupo,
    ':id_desafio' => $id_desafio,
]);

if (!$stmt->fetch()) {
    $stmt = $pdo->prepare(
        "INSERT INTO participacao
            (progresso_atual, status, id_usuario, id_grupo, id_desafio)
         VALUES
            (0, 'ativo', :id_usuario, :id_grupo, :id_desafio)"
    );
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_grupo' => $id_grupo,
        ':id_desafio' => $id_desafio,
    ]);
}

header('Location: ../html/grupos.php?participando=1');
exit;
