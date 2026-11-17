
<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_convite = (int) ($_POST['id_convite'] ?? 0);
$acao = $_POST['acao'] ?? '';

if ($id_convite <= 0 || !in_array($acao, ['aceitar', 'recusar'], true)) {
    header('Location: ../html/grupos.php?erro=convite');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT id_grupo
        FROM convite_grupo
        WHERE id_convite = :id_convite
          AND id_usuario_convidado = :id_usuario
          AND status = 'pendente'
        FOR UPDATE
    ");

    $stmt->execute([
        ':id_convite' => $id_convite,
        ':id_usuario' => $id_usuario
    ]);

    $convite = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$convite) {
        throw new Exception('Convite inválido ou já respondido.');
    }

    if ($acao === 'aceitar') {
        $stmt = $pdo->prepare("
            INSERT INTO membro_usuario
                (id_usuario, id_grupo, papel, data_entrada)
            SELECT :id_usuario, :id_grupo, 'Membro', CURDATE()
            WHERE NOT EXISTS (
                SELECT 1
                FROM membro_usuario
                WHERE id_usuario = :id_usuario_verificacao
                  AND id_grupo = :id_grupo_verificacao
            )
        ");

        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_grupo' => $convite['id_grupo'],
            ':id_usuario_verificacao' => $id_usuario,
            ':id_grupo_verificacao' => $convite['id_grupo']
        ]);

        $novo_status = 'aceito';
    } else {
        $novo_status = 'recusado';
    }

    $stmt = $pdo->prepare("
        UPDATE convite_grupo
        SET status = :status
        WHERE id_convite = :id_convite
          AND id_usuario_convidado = :id_usuario
          AND status = 'pendente'
    ");

    $stmt->execute([
        ':status' => $novo_status,
        ':id_convite' => $id_convite,
        ':id_usuario' => $id_usuario
    ]);

    $pdo->commit();

    header('Location: ../html/grupos.php?convite_' . $acao . '=1');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    header('Location: ../html/grupos.php?erro=convite');
    exit;
}
