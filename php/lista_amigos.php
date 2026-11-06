
<?php

// RF33 – Lista de amigos do usuário logado (somente solicitações aceitas).

function buscar_amigos(PDO $pdo, int $id_usuario_logado): array
{
    // Mesmas cores de avatar usadas em html/grupos.php
    $cores_avatar = ['#00BC7D', '#2B7FFF', '#F6339A', '#FE9A00', '#8E51FF', '#00B8DB'];

    // Busca os amigos do usuário logado, considerando os dois lados da amizade.
    $sql = "SELECT u.id_usuario, u.nome
            FROM amizade a
            JOIN usuario u
              ON u.id_usuario = IF(
                  a.id_usuario_remetente = ?,
                  a.id_usuario_destinatario,
                  a.id_usuario_remetente
              )
            WHERE (a.id_usuario_remetente = ? OR a.id_usuario_destinatario = ?)
              AND a.status = 'aceita'
            ORDER BY u.nome ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario_logado, $id_usuario_logado, $id_usuario_logado]);

    $amigos = [];

    foreach ($stmt->fetchAll() as $i => $linha) {
        $amigos[] = [
            'id_usuario' => (int) $linha['id_usuario'],
            'nome'       => $linha['nome'],
            'cor'        => $cores_avatar[$i % count($cores_avatar)],
        ];
    }

    return $amigos;
}
