<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/grupos.php');
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];
$id_grupo = filter_var($_POST['id_grupo'] ?? null, FILTER_VALIDATE_INT);
$id_membro = filter_var($_POST['id_membro'] ?? null, FILTER_VALIDATE_INT);
$acao = $_POST['acao'] ?? '';

if ($id_grupo === false || $id_grupo < 1
    || $id_membro === false || $id_membro < 1
    || !in_array($acao, ['remover', 'promover'], true)) {
    header('Location: ../html/grupos.php?erro=membro_invalido');
    exit;
}

$stmt = $pdo->prepare("SELECT id_grupo
                       FROM membro_usuario
                       WHERE id_grupo = :id_grupo
                         AND id_usuario = :id_usuario
                         AND papel = 'Administrador'");
$stmt->execute([
    ':id_grupo'   => $id_grupo,
    ':id_usuario' => $id_usuario,
]);

if (!$stmt->fetch()) {
    header('Location: ../html/grupos.php?erro=permissao_membros');
    exit;
}

if ($acao === 'promover') {
    $stmt = $pdo->prepare("UPDATE membro_usuario AS alvo
                           INNER JOIN membro_usuario AS administrador
                                   ON administrador.id_grupo = alvo.id_grupo
                                  AND administrador.id_usuario = :id_administrador
                                  AND administrador.papel = 'Administrador'
                           SET alvo.papel = 'Administrador'
                           WHERE alvo.id_grupo = :id_grupo
                             AND alvo.id_usuario = :id_membro
                             AND alvo.id_usuario <> :id_usuario_atual
                             AND alvo.papel <> 'Administrador'");
    $stmt->execute([
        ':id_administrador' => $id_usuario,
        ':id_grupo'         => $id_grupo,
        ':id_membro'        => $id_membro,
        ':id_usuario_atual' => $id_usuario,
    ]);

    $resultado = 'membro_promovido';
} else {
    $stmt = $pdo->prepare("DELETE alvo
                           FROM membro_usuario AS alvo
                           INNER JOIN membro_usuario AS administrador
                                   ON administrador.id_grupo = alvo.id_grupo
                                  AND administrador.id_usuario = :id_administrador
                                  AND administrador.papel = 'Administrador'
                           LEFT JOIN membro_usuario AS outro_administrador
                                  ON outro_administrador.id_grupo = alvo.id_grupo
                                 AND outro_administrador.papel = 'Administrador'
                                 AND outro_administrador.id_usuario <> alvo.id_usuario
                           WHERE alvo.id_grupo = :id_grupo
                             AND alvo.id_usuario = :id_membro
                             AND alvo.id_usuario <> :id_usuario_atual
                             AND (alvo.papel <> 'Administrador'
                                  OR outro_administrador.id_usuario IS NOT NULL)");
    $stmt->execute([
        ':id_administrador' => $id_usuario,
        ':id_grupo'         => $id_grupo,
        ':id_membro'        => $id_membro,
        ':id_usuario_atual' => $id_usuario,
    ]);

    $resultado = 'membro_removido';
}

if ($stmt->rowCount() === 0) {
    header('Location: ../html/grupos.php?erro=membro_operacao');
    exit;
}

header('Location: ../html/grupos.php?' . $resultado . '=1');
exit;
