
<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_solicitacao = (int) ($_POST['id_solicitacao'] ?? 0);
$acao = $_POST['acao'] ?? '';

if (
    $id_solicitacao <= 0 ||
    !in_array($acao, ['aprovar', 'recusar'], true)
) {
    header('Location: ../html/grupos.php?erro=solicitacao');
    exit;
}

try {
    $pdo->beginTransaction();

    // Busca a solicitação e verifica se o usuário é administrador.
    $stmt = $pdo->prepare("
        SELECT s.id_grupo, s.id_usuario
        FROM solicitacao_ingresso_grupo s
        INNER JOIN membro_usuario m
            ON m.id_grupo = s.id_grupo
           AND m.id_usuario = :id_admin
           AND m.papel = 'Administrador'
        WHERE s.id_solicitacao = :id_solicitacao
          AND s.status = 'pendente'
        FOR UPDATE
    ");

    $stmt->execute([
        ':id_admin' => $id_usuario,
        ':id_solicitacao' => $id_solicitacao
    ]);

    $solicitacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$solicitacao) {
        throw new Exception('Solicitação inválida.');
    }

    if ($acao === 'aprovar') {
        // Adiciona o usuário ao grupo.
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
            ':id_usuario' => $solicitacao['id_usuario'],
            ':id_grupo' => $solicitacao['id_grupo'],
            ':id_usuario_verificacao' => $solicitacao['id_usuario'],
            ':id_grupo_verificacao' => $solicitacao['id_grupo']
        ]);

        $novo_status = 'aceita';
    } else {
        $novo_status = 'recusada';
    }

    // Atualiza o status da solicitação.
    $stmt = $pdo->prepare("
        UPDATE solicitacao_ingresso_grupo
        SET status = :status
        WHERE id_solicitacao = :id_solicitacao
          AND status = 'pendente'
    ");

    $stmt->execute([
        ':status' => $novo_status,
        ':id_solicitacao' => $id_solicitacao
    ]);

    $pdo->commit();

    header('Location: ../html/grupos.php?solicitacao_' . $acao . '=1');
    exit;

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    header('Location: ../html/grupos.php?erro=solicitacao');
    exit;
}
