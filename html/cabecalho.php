<?php
require_once __DIR__ . '/../php/icones.php';

$nome_usuario = $_SESSION['nome'] ?? 'Usuário';

// Exemplo: virá da tabela ranking_temporada (Sprint 4)
$pontos_mes = $pontos_mes ?? 1240;

$abas = [
    'inicio'          => ['Início', 'inicio.php'],
    'desafios'        => ['Desafios Globais', 'desafios.php'],
    'grupos'          => ['Grupos', 'grupos.php'],
    'condicionamento' => ['Condicionamento', 'condicionamento.php'],
    'ranking'         => ['Ranking', 'ranking.php'],
];
?>
<aside class="lateral">
    <div class="lateral-marca">
        <div class="lateral-logo">
            <img src="../img/mascote.png" alt="">
        </div>
        <div>
            <strong>Health Challenges</strong>
            <small>Versão 1.0</small>
        </div>
    </div>

    <div class="lateral-usuario">
        <span class="avatar"><?= htmlspecialchars(iniciais($nome_usuario)) ?></span>
        <div>
            <strong><?= htmlspecialchars($nome_usuario) ?></strong>
            <span>⭐ <?= $pontos_mes ?> pts</span>
        </div>
    </div>

    <p class="lateral-titulo">MENU PRINCIPAL</p>

    <nav class="lateral-menu" aria-label="Menu principal">
        <?php foreach ($abas as $chave => $aba): ?>
            <a href="<?= $aba[1] ?>" class="<?= $pagina_atual === $chave ? 'ativo' : '' ?>"
               <?= $pagina_atual === $chave ? 'aria-current="page"' : '' ?>>
                <?= icone($chave, 22) ?>
                <span><?= $aba[0] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <a href="../php/logout.php" class="lateral-sair">
        <?= icone('sair', 22) ?>
        <span>Sair</span>
    </a>
</aside>

<header class="topo-app">
    <h2><?= htmlspecialchars($titulo_topo) ?></h2>
    <div class="topo-direita">
        <span class="selo-pontos">⭐ <?= $pontos_mes ?> pts<span class="selo-extra"> este mês</span></span>
        <a href="../php/logout.php" class="topo-sair" aria-label="Sair"><?= icone('sair', 20) ?></a>
    </div>
</header>