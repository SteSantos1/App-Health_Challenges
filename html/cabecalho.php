<?php
require_once __DIR__ . '/../php/icones.php';

$nome_usuario = $_SESSION['nome'] ?? 'Usuário';

if (!isset($pontos_mes)) {
    require_once __DIR__ . '/../php/conexao.php';
    require_once __DIR__ . '/../php/pontos.php';
    $pontos_mes = pontos_do_mes($pdo, (int) ($_SESSION['id_usuario'] ?? 0));
}

$abas = [
    'inicio'          => ['Início', 'inicio.php'],
    'desafios'        => ['Desafios Globais', 'desafios.php'],
    'grupos'          => ['Grupos', 'grupos.php'],
    'condicionamento' => ['Condicionamento', 'condicionamento.php'],
    'ranking'         => ['Ranking', 'ranking.php'],
    'amigos' => ['Amigos', 'amigos.php'],
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
        <span class="avatar" aria-hidden="true"><?= htmlspecialchars(iniciais($nome_usuario)) ?></span>
        <div>
            <strong><?= htmlspecialchars($nome_usuario) ?></strong>
            <span><span aria-hidden="true">⭐</span> <span data-pontos-mes><?= $pontos_mes ?></span> pts</span>
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
    <p class="topo-titulo"><?= htmlspecialchars($titulo_topo) ?></p>
    <div class="topo-direita">
        <button type="button" class="btn-daltonismo" data-alternar-daltonismo aria-pressed="false" aria-label="Modo de cores para daltonismo" title="Alternar contraste para daltonismo">
            <?= icone('contraste', 18) ?>
            <span class="rotulo-daltonismo">Daltonismo</span>
        </button>
        <span class="selo-pontos"><span aria-hidden="true">⭐</span> <span data-pontos-mes><?= $pontos_mes ?></span> pts<span class="selo-extra"> este mês</span></span>
        <a href="../php/logout.php" class="topo-sair" aria-label="Sair"><?= icone('sair', 20) ?></a>
    </div>
</header>