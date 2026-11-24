<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';

header('Content-Type: application/json; charset=utf-8');

$termo = trim($_GET['termo'] ?? '');

if ($termo === '') {
    echo json_encode(['usuarios' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

$id_usuario = (int) $_SESSION['id_usuario'];

$stmt = $pdo->prepare(
    "SELECT u.id_usuario, u.nome, a.status
     FROM usuario u
     LEFT JOIN amizade a
            ON (a.id_usuario_remetente = :eu1 AND a.id_usuario_destinatario = u.id_usuario)
            OR (a.id_usuario_remetente = u.id_usuario AND a.id_usuario_destinatario = :eu2)
     WHERE u.id_usuario <> :eu3
       AND u.nome LIKE :nome
     ORDER BY u.nome ASC
     LIMIT 20"
);
$stmt->execute([
    ':eu1'  => $id_usuario,
    ':eu2'  => $id_usuario,
    ':eu3'  => $id_usuario,
    ':nome' => '%' . addcslashes($termo, '%_\\') . '%',
]);

$usuarios = [];
foreach ($stmt->fetchAll() as $linha) {
    $relacao = null;
    if ($linha['status'] === 'aceita') {
        $relacao = 'amigo';
    } elseif ($linha['status'] === 'pendente') {
        $relacao = 'pendente';
    } elseif ($linha['status'] !== null) {
        $relacao = 'outra';
    }

    $usuarios[] = [
        'id_usuario' => (int) $linha['id_usuario'],
        'nome'       => $linha['nome'],
        'relacao'    => $relacao,
    ];
}

echo json_encode(['usuarios' => $usuarios], JSON_UNESCAPED_UNICODE);
