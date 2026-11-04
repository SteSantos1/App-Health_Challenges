<?php
// RF17 – Ranking diário: pontuação obtida pelos usuários HOJE (todos os usuários cadastrados).

function buscar_ranking_diario(PDO $pdo, int $id_usuario_logado): array
{
    // Mesmas cores de avatar usadas em html/grupos.php
    $cores_avatar = ['#00BC7D', '#2B7FFF', '#F6339A', '#FE9A00', '#8E51FF', '#00B8DB'];

    // LEFT JOIN a partir de usuario: quem ainda não concluiu desafio hoje aparece com 0 (e não NULL).
    // Os pontos vêm de desafio_diario.pontos_recompensa, via registro_conclusao (RF13), só de hoje.
    $sql = "SELECT u.id_usuario, u.nome, COALESCE(SUM(dd.pontos_recompensa), 0) AS pontos
            FROM usuario u
            LEFT JOIN registro_conclusao rc
                   ON rc.id_usuario = u.id_usuario
                  AND DATE(rc.data_hora) = CURDATE()
            LEFT JOIN desafio_diario dd
                   ON dd.id_desafio_diario = rc.id_desafio_diario
            GROUP BY u.id_usuario, u.nome
            ORDER BY pontos DESC, u.nome ASC";

    $ranking = [];
    foreach ($pdo->query($sql)->fetchAll() as $i => $linha) {
        $sou_eu = ((int) $linha['id_usuario'] === $id_usuario_logado);
        $ranking[] = [
            'nome'   => $linha['nome'],
            'pontos' => (int) $linha['pontos'],
            'cor'    => $sou_eu ? '#FF2056' : $cores_avatar[$i % count($cores_avatar)],
            'eu'     => $sou_eu,
        ];
    }

    return $ranking;
}