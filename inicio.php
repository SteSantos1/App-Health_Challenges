<?php


require_once 'verifica_sessao.php';

$nome = $_SESSION['nome'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | Health Challenges</title>
    <?php include 'login_style.php'; ?>
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

        <a href="logout.php" class="btn-sair">Sair</a>
    </header>

    <main class="conteudo">
        <h1>Olá, <?= htmlspecialchars($nome) ?>!</h1>
        <p>Login realizado com sucesso. Esta é a sua página inicial.</p>
    </main>
</body>

</html>
