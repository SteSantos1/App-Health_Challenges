<?php

function catalogo_conquistas(): array
{
    return [
        'pontuacao_50' => [
            'emoji' => '🌟',
            'nome' => 'Faísca Inicial',
            'criterio' => '50 pontos acumulados',
            'tipo' => 'pontuacao',
            'limite' => 50,
        ],
        'pontuacao_250' => [
            'emoji' => '🔥',
            'nome' => 'Pegando Fogo',
            'criterio' => '250 pontos acumulados',
            'tipo' => 'pontuacao',
            'limite' => 250,
        ],
        'pontuacao_500' => [
            'emoji' => '💎',
            'nome' => 'Máquina de Pontos',
            'criterio' => '500 pontos acumulados',
            'tipo' => 'pontuacao',
            'limite' => 500,
        ],
        'pontuacao_1000' => [
            'emoji' => '🏆',
            'nome' => 'Lenda dos Mil',
            'criterio' => '1.000 pontos acumulados',
            'tipo' => 'pontuacao',
            'limite' => 1000,
        ],
        'ranking_1' => [
            'emoji' => '🥇',
            'nome' => 'Rei do Ranking',
            'criterio' => '1º lugar no ranking mensal',
            'tipo' => 'ranking',
            'limite' => 1,
        ],
        'ranking_2' => [
            'emoji' => '🥈',
            'nome' => 'Prata de Respeito',
            'criterio' => '2º lugar no ranking mensal',
            'tipo' => 'ranking',
            'limite' => 2,
        ],
        'ranking_3' => [
            'emoji' => '🥉',
            'nome' => 'Dono do Pódio',
            'criterio' => '3º lugar no ranking mensal',
            'tipo' => 'ranking',
            'limite' => 3,
        ],
    ];
}

function garantir_tabela_conquistas(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS conquista_usuario (
            id_conquista_usuario INT NOT NULL AUTO_INCREMENT,
            id_usuario INT NOT NULL,
            chave_conquista VARCHAR(50) NOT NULL,
            data_desbloqueio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id_conquista_usuario),
            UNIQUE KEY uq_conquista_usuario (id_usuario, chave_conquista)
        )'
    );
}

function registrar_conquista(PDO $pdo, int $idUsuario, string $chave): void
{
    garantir_tabela_conquistas($pdo);

    $stmt = $pdo->prepare(
        'INSERT INTO conquista_usuario (id_usuario, chave_conquista)
         VALUES (:id_usuario, :chave_conquista)
         ON DUPLICATE KEY UPDATE data_desbloqueio = conquista_usuario.data_desbloqueio'
    );
    $stmt->execute([
        ':id_usuario' => $idUsuario,
        ':chave_conquista' => $chave,
    ]);
}

function desbloquear_conquistas_por_pontuacao(PDO $pdo, int $idUsuario): void
{
    $stmt = $pdo->prepare(
        'SELECT COALESCE(SUM(dd.pontos_recompensa), 0)
         FROM registro_conclusao rc
         INNER JOIN desafio_diario dd ON dd.id_desafio_diario = rc.id_desafio_diario
         WHERE rc.id_usuario = :id_usuario
           AND rc.status_conclusao = 1'
    );
    $stmt->execute([':id_usuario' => $idUsuario]);
    $pontosAcumulados = (int) $stmt->fetchColumn();

    foreach (catalogo_conquistas() as $chave => $conquista) {
        if ($conquista['tipo'] === 'pontuacao' && $pontosAcumulados >= $conquista['limite']) {
            registrar_conquista($pdo, $idUsuario, $chave);
        }
    }
}

function desbloquear_conquistas_por_ranking(PDO $pdo, int $idUsuario, int $posicao): void
{
    foreach (catalogo_conquistas() as $chave => $conquista) {
        if ($conquista['tipo'] === 'ranking' && $posicao === $conquista['limite']) {
            registrar_conquista($pdo, $idUsuario, $chave);
        }
    }
}

function obter_conquistas_recentes(PDO $pdo, int $idUsuario, int $limite = 4): array
{
    garantir_tabela_conquistas($pdo);

    $stmt = $pdo->prepare(
        'SELECT chave_conquista
         FROM conquista_usuario
         WHERE id_usuario = :id_usuario
         ORDER BY data_desbloqueio DESC, id_conquista_usuario DESC
         LIMIT :limite'
    );
    $stmt->bindValue(':id_usuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();

    $catalogo = catalogo_conquistas();
    $conquistas = [];
    foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $chave) {
        if (isset($catalogo[$chave])) {
            $conquistas[] = $catalogo[$chave];
        }
    }

    return $conquistas;
}
