<?php
require_once __DIR__ . '/../php/verifica_sessao.php';

$pagina_atual = 'amigos';
$titulo_topo = 'Amigos';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amigos | Health Challenges</title>
    <?php include __DIR__ . '/../css/app_style.php'; ?>
</head>
<body class="app">
    <?php include __DIR__ . '/cabecalho.php'; ?>

    <main class="conteudo">
        <div class="cabeca-pagina">
            <div>
                <h1>Amigos</h1>
                <p>Encontre usuários e envie solicitações de amizade.</p>
            </div>
        </div>

        <!-- O conteúdo da página entra aqui -->
    </main>
</body>
</html>
