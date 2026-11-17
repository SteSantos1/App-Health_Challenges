
<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

// Dados do usuário e do grupo
$id_usuario = (int) $_SESSION['id_usuario'];
$id_grupo = (int) ($_POST['id_grupo'] ?? 0);

// Verifica se o ID do grupo é válido
if ($id_grupo <= 0) {
    header('Location: ../html/grupos.php');
    exit;
}

try {
    // Verifica se o grupo existe
    $stmt = $pdo->prepare("
        SELECT id_grupo
        FROM grupo
        WHERE id_grupo = :id_grupo
    ");
    $stmt->execute([':id_grupo' => $id_grupo]);

    if (!$stmt->fetch()) {
        header('Location: ../html/grupos.php?erro=grupo');
        exit;
    }

    // Verifica se o usuário já participa do grupo
    $stmt = $pdo->prepare("
        SELECT id_usuario
        FROM membro_usuario
        WHERE id_grupo = :id_grupo
          AND id_usuario = :id_usuario
    ");
    $stmt->execute([
        ':id_grupo' => $id_grupo,
        ':id_usuario' => $id_usuario
    ]);

    if ($stmt->fetch()) {
        header('Location: ../html/grupos.php?erro=membro');
        exit;
    }

    // Verifica se já existe uma solicitação pendente
    $stmt = $pdo->prepare("
        SELECT id_solicitacao
        FROM solicitacao_ingresso_grupo
        WHERE id_grupo = :id_grupo
          AND id_usuario = :id_usuario
          AND status = 'pendente'
        LIMIT 1
    ");
    $stmt->execute([
        ':id_grupo' => $id_grupo,
        ':id_usuario' => $id_usuario
    ]);

    if ($stmt->fetch()) {
        header('Location: ../html/grupos.php?erro=pendente');
        exit;
    }

    // Registra a solicitação de ingresso
    $stmt = $pdo->prepare("
        INSERT INTO solicitacao_ingresso_grupo
            (id_grupo, id_usuario, status)
        VALUES
            (:id_grupo, :id_usuario, 'pendente')
    ");

    $stmt->execute([
        ':id_grupo' => $id_grupo,
        ':id_usuario' => $id_usuario
    ]);

    header('Location: ../html/grupos.php?solicitacao=1');
    exit;

} catch (PDOException $e) {
    header('Location: ../html/grupos.php?erro=salvar');
    exit;
}
