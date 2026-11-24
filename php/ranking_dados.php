<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/verifica_sessao.php';
require_once __DIR__ . '/conquistas.php';

$pagina_atual = 'ranking';
$titulo_topo = 'Ranking';

$id_usuario = $_SESSION['id_usuario'];
$mes_ano = date('Y-m');
$inicio_mes = date('Y-m-01');

$meses = ['', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
$mes_atual = $meses[(int) date('n')];
$primeiro_nome = explode(' ', trim($_SESSION['nome']))[0];

$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("DELETE FROM ranking_temporada WHERE mes_ano = :mes_ano");
    $stmt->execute([':mes_ano' => $mes_ano]);

    $stmt = $pdo->prepare("INSERT INTO ranking_temporada (mes_ano, pontuacao_periodo, id_usuario)
                           SELECT :mes_ano, SUM(dd.pontos_recompensa), rc.id_usuario
                           FROM registro_conclusao rc
                           INNER JOIN desafio_diario dd ON dd.id_desafio_diario = rc.id_desafio_diario
                           WHERE rc.status_conclusao = 1
                             AND rc.data_hora >= :inicio_mes
                             AND rc.data_hora < CURDATE()
                           GROUP BY rc.id_usuario");
    $stmt->execute([
        ':mes_ano'    => $mes_ano,
        ':inicio_mes' => $inicio_mes,
    ]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    throw $e;
}

$stmt = $pdo->prepare("SELECT u.id_usuario, u.nome, COALESCE(rt.pontuacao_periodo, 0) AS pontos
                       FROM usuario u
                       LEFT JOIN ranking_temporada rt
                              ON rt.id_usuario = u.id_usuario
                             AND rt.mes_ano = :mes_ano
                       ORDER BY pontos DESC, u.nome ASC");
$stmt->execute([':mes_ano' => $mes_ano]);
$linhas_mensal = $stmt->fetchAll();

foreach ($linhas_mensal as $indice => $linha) {
    if ((int) $linha['pontos'] > 0) {
        desbloquear_conquistas_por_ranking($pdo, (int) $linha['id_usuario'], $indice + 1);
    }
}

$cores = ['#9E1B5E', '#005AB5', '#00684A', '#B84700', '#6A359C', '#005F73'];

function montar_ranking($linhas, $id_usuario, $cores)
{
    $lista = [];

    foreach ($linhas as $linha) {
        $lista[] = [
            'nome'   => $linha['nome'],
            'pontos' => (int) $linha['pontos'],
            'cor'    => $cores[$linha['id_usuario'] % count($cores)],
            'eu'     => (int) $linha['id_usuario'] === (int) $id_usuario,
        ];
    }

    return $lista;
}

// Exemplo: o ranking diário (RF17) virá da tabela ranking_diario
$rankings = [
    'mensal' => montar_ranking($linhas_mensal, $id_usuario, $cores),
    'diario' => [
        ['nome' => 'Carlos Oliveira', 'pontos' => 150, 'cor' => '#005AB5'],
        ['nome' => $_SESSION['nome'], 'pontos' => 100, 'cor' => '#B84700', 'eu' => true],
        ['nome' => 'Ana Rodrigues',   'pontos' => 100, 'cor' => '#005F73'],
        ['nome' => 'Marina Santos',   'pontos' => 50,  'cor' => '#9E1B5E'],
        ['nome' => 'Tiago Souza',     'pontos' => 50,  'cor' => '#00684A'],
    ],
];

$pontos_mes = 0;
foreach ($rankings['mensal'] as $pessoa) {
    if ($pessoa['eu']) {
        $pontos_mes = $pessoa['pontos'];
    }
}

$textos = [
    'mensal' => [
        'subtitulo' => "Classificação mensal de $mes_atual. Atualizado diariamente com os pontos até ontem.",
        'podio'     => "Pódio de $mes_atual",
    ],
    'diario' => [
        'subtitulo' => 'Pontuação obtida pelos usuários hoje (' . date('d/m') . ').',
        'podio'     => 'Pódio de hoje',
    ],
];

$medalhas = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
