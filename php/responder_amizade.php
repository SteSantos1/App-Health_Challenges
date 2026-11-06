<?php
require_once __DIR__ . '/env.php';
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/amigos.php');
    exit;
}

$id_destinatario = (int) $_SESSION['id_usuario'];
$id_remetente = filter_input(INPUT_POST, 'id_remetente', FILTER_VALIDATE_INT);
$acao = $_POST['acao'] ?? '';

if (!$id_remetente || $id_remetente < 1 || !in_array($acao, ['aceitar', 'recusar'], true)) {
    header('Location: ../html/amigos.php?erro=solicitacao');
    exit;
}

// Verifica se existe uma solicitação pendente desse remetente para o usuário logado
$stmt = $pdo->prepare(
    "SELECT status
     FROM amizade
     WHERE id_usuario_remetente = :remetente
       AND id_usuario_destinatario = :destinatario
       AND status = 'pendente'
     LIMIT 1"
);
$stmt->execute([
    ':remetente'    => $id_remetente,
    ':destinatario' => $id_destinatario,
]);

if (!$stmt->fetch()) {
    header('Location: ../html/amigos.php?erro=solicitacao');
    exit;
}

if ($acao === 'aceitar') {
    // RF31: Aceitar solicitação de amizade recebida ('aceita' cabe em TEXTO 9)
    $stmt_aceitar = $pdo->prepare(
        "UPDATE amizade
         SET status = 'aceita', data_conexao = NOW()
         WHERE id_usuario_remetente = :remetente
           AND id_usuario_destinatario = :destinatario"
    );
    $stmt_aceitar->execute([
        ':remetente'    => $id_remetente,
        ':destinatario' => $id_destinatario,
    ]);

    header('Location: ../html/amigos.php?solicitacao=aceita');
    exit;
} else {
    // RF32: Recusar solicitação de amizade recebida
    // Remove o registro pendente para permitir novas solicitações no futuro
    $stmt_recusar = $pdo->prepare(
        "DELETE FROM amizade
         WHERE id_usuario_remetente = :remetente
           AND id_usuario_destinatario = :destinatario
           AND status = 'pendente'"
    );
    $stmt_recusar->execute([
        ':remetente'    => $id_remetente,
        ':destinatario' => $id_destinatario,
    ]);

    header('Location: ../html/amigos.php?solicitacao=recusada');
    exit;
}
