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
$progresso_atual = filter_input(INPUT_POST, 'progresso_atual', FILTER_VALIDATE_INT);

if ($id_grupo === false || $id_grupo === null || $id_grupo < 1
    || $id_desafio === false || $id_desafio === null || $id_desafio < 1
    || $progresso_atual === false || $progresso_atual === null || $progresso_atual < 0 || $progresso_atual > 1000000) {
    header('Location: ../html/grupos.php?erro=progresso_dados');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id_grupo
     FROM membro_usuario
     WHERE id_grupo = :id_grupo
       AND id_usuario = :id_usuario"
);
$stmt->execute([
    ':id_grupo'   => $id_grupo,
    ':id_usuario' => $id_usuario,
]);

if (!$stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=participacao_grupo');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id_desafio, valor_meta
     FROM desafio
     WHERE id_desafio = :id_desafio
       AND id_grupo = :id_grupo
       AND CURDATE() BETWEEN data_inicio AND data_fim"
);
$stmt->execute([
    ':id_desafio' => $id_desafio,
    ':id_grupo'   => $id_grupo,
]);
$desafio = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$desafio) {
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
    ':id_grupo'   => $id_grupo,
    ':id_desafio' => $id_desafio,
]);
$participacao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$participacao) {
    header('Location: ../html/grupos.php?erro=progresso_nao_participa');
    exit;
}

$novo_status = $progresso_atual >= (int) $desafio['valor_meta'] ? 'concluido' : 'ativo';

$stmt = $pdo->prepare(
    "UPDATE participacao
     SET progresso_atual = :progresso_atual,
         status = :status
     WHERE id_participacao = :id_participacao"
);
$stmt->execute([
    ':progresso_atual' => $progresso_atual,
    ':status'          => $novo_status,
    ':id_participacao' => (int) $participacao['id_participacao'],
]);

header('Location: ../html/grupos.php?progresso_salvo=1');
exit;
