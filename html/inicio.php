<?php


require_once __DIR__ . '/../php/verifica_sessao.php';

$nome = $_SESSION['nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | Health Challenges</title>
    <?php include __DIR__ . '/../css/login_style.php'; ?>
    <style>
        .menu { display: flex; gap: 8px; padding: 0 32px; background-color: var(--branco); border-bottom: 1px solid var(--borda); overflow-x: auto; }
        .menu a { padding: 14px 18px; color: var(--texto-suave); font-weight: 700; text-decoration: none; border-bottom: 3px solid transparent; white-space: nowrap; }
        .menu a:hover { color: var(--verde); }
        .menu a.ativo { color: var(--verde); border-bottom-color: var(--verde); }
    </style>
</head>

<body>
    
    <header class="topo">
        <div class="marca">
            <span class="marca-icone" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                </svg>
            </span>
            <span class="marca-nome">Health Challenges</span>
        </div>

        <a href="../php/logout.php" class="btn-sair">Sair</a>
    </header>

    <nav class="menu">
        <a href="inicio.php" class="ativo">Início</a>
        <a href="#">Desafios Globais</a>
        <a href="#">Grupos</a>
        <a href="#">Condicionamento</a>
        <a href="#">Ranking</a>
    </nav>

    <main class="conteudo">
        <h1>Olá, <?= htmlspecialchars($nome) ?>!</h1>
        <p>Login realizado com sucesso. Esta é a sua página inicial.</p>
    </main>
</body>

</html>
