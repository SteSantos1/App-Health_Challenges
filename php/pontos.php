<?php

function pontos_do_mes(PDO $pdo, int $id_usuario): int
{
    $stmt = $pdo->prepare(
        'SELECT COALESCE(SUM(dd.pontos_recompensa), 0)
         FROM registro_conclusao rc
         INNER JOIN desafio_diario dd ON dd.id_desafio_diario = rc.id_desafio_diario
         WHERE rc.id_usuario = :id_usuario
           AND rc.status_conclusao = 1
           AND rc.data_hora >= :inicio_mes'
    );
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':inicio_mes' => date('Y-m-01'),
    ]);

    return (int) $stmt->fetchColumn();
}
