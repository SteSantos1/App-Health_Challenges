<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/inicio.php');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT dd.id_desafio_diario, dd.quantidade_exigida, dd.pontos_recompensa
     FROM desafio_escolhido AS de
     INNER JOIN desafio_diario AS dd
             ON dd.id_desafio_diario = de.id_desafio_diario
     WHERE de.id_usuario = :id_usuario
       AND de.data_escolha = CURDATE()
       AND dd.data_sorteio = CURDATE()
     LIMIT 1"
);
$stmt->execute([':id_usuario' => $_SESSION['id_usuario']]);
$desafio = $stmt->fetch();

if (!$desafio) {
    header('Location: ../html/inicio.php?erro=sem_desafio');
    exit;
}

$stmt = $pdo->prepare(
    "SELECT id_registro
     FROM registro_conclusao
     WHERE id_usuario = :id_usuario
       AND id_desafio_diario = :id_desafio_diario
       AND data_hora >= CURDATE()
       AND data_hora < CURDATE() + INTERVAL 1 DAY
     LIMIT 1"
);
$stmt->execute([
    ':id_usuario' => $_SESSION['id_usuario'],
    ':id_desafio_diario' => $desafio['id_desafio_diario'],
]);

if ($stmt->fetch()) {
    header('Location: ../html/inicio.php?concluido=ja');
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO registro_conclusao
        (quantidade_feita, pontos_ganhos, data_hora, status_conclusao, id_usuario, id_desafio_diario)
     VALUES
        (:quantidade_feita, :pontos_ganhos, NOW(), 1, :id_usuario, :id_desafio_diario)"
);
$stmt->execute([
    ':quantidade_feita' => $desafio['quantidade_exigida'],
    ':pontos_ganhos' => $desafio['pontos_recompensa'],
    ':id_usuario' => $_SESSION['id_usuario'],
    ':id_desafio_diario' => $desafio['id_desafio_diario'],
]);

header('Location: ../html/inicio.php?concluido=1');
exit;
