
<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_grupo = (int) ($_POST['id_grupo'] ?? 0);
$id_convidado = (int) ($_POST['id_usuario_convidado'] ?? 0);

if ($id_grupo <= 0 || $id_convidado <= 0 || $id_convidado === $id_usuario) {
    header('Location: ../html/grupos.php?erro=convite');
    exit;
}

try {
    // Verifica se o usuário logado é administrador do grupo.
    $stmt = $pdo->prepare("
        SELECT id_grupo
        FROM membro_usuario
        WHERE id_grupo = :id_grupo
          AND id_usuario = :id_usuario
          AND papel = 'Administrador'
    ");

    $stmt->execute([
        ':id_grupo' => $id_grupo,
        ':id_usuario' => $id_usuario
    ]);

    if (!$stmt->fetch()) {
        header('Location: ../html/grupos.php?erro=convite_permissao');
        exit;
    }

    // Verifica se o convidado já participa do grupo.
    $stmt = $pdo->prepare("
        SELECT id_usuario
        FROM membro_usuario
        WHERE id_grupo = :id_grupo
          AND id_usuario = :id_convidado
    ");

    $stmt->execute([
        ':id_grupo' => $id_grupo,
        ':id_convidado' => $id_convidado
    ]);

    if ($stmt->fetch()) {
        header('Location: ../html/grupos.php?erro=convite');
        exit;
    }

    // Verifica se já existe um convite pendente.
    $stmt = $pdo->prepare("
        SELECT id_convite
        FROM convite_grupo
        WHERE id_grupo = :id_grupo
          AND id_usuario_convidado = :id_convidado
          AND status = 'pendente'
    ");

    $stmt->execute([
        ':id_grupo' => $id_grupo,
        ':id_convidado' => $id_convidado
    ]);

    if ($stmt->fetch()) {
        header('Location: ../html/grupos.php?erro=convite_existente');
        exit;
    }

    // Registra o convite.
    $stmt = $pdo->prepare("
        INSERT INTO convite_grupo
            (id_grupo, id_usuario_convidado, id_usuario_remetente)
        VALUES
            (:id_grupo, :id_convidado, :id_remetente)
    ");

    $stmt->execute([
        ':id_grupo' => $id_grupo,
        ':id_convidado' => $id_convidado,
        ':id_remetente' => $id_usuario
    ]);

    header('Location: ../html/grupos.php?convite_enviado=1');
    exit;

} catch (PDOException $e) {
    header('Location: ../html/grupos.php?erro=convite');
    exit;
}
