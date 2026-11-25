<?php
require_once __DIR__ . '/../php/conexao.php';
require_once __DIR__ . '/../php/email_codigo.php';

if (isset($_SESSION['id_usuario'])) {
    header('Location: inicio.php');
    exit;
}

if (empty($_SESSION['verificar_email_id'])) {
    header('Location: index.php');
    exit;
}

$id_usuario = (int) $_SESSION['verificar_email_id'];

$stmt = $pdo->prepare('SELECT email, email_verificado FROM usuario WHERE id_usuario = :id');
$stmt->execute([':id' => $id_usuario]);
$usuario = $stmt->fetch();

if (!$usuario || (int) $usuario['email_verificado'] === 1) {
    unset($_SESSION['verificar_email_id']);
    header('Location: index.php');
    exit;
}

$email_mascarado = mascarar_email($usuario['email']);
$espera = segundos_para_reenviar($pdo, $id_usuario);

$mensagens_erro = [
    'incorreto' => 'Código incorreto. Confira o e-mail e tente de novo.',
    'formato' => 'Digite os 6 números do código.',
    'expirado' => 'Esse código expirou. Peça um novo abaixo.',
    'tentativas' => 'Muitas tentativas erradas. Peça um novo código abaixo.',
    'sem-codigo' => 'Não há código ativo. Peça um novo abaixo.',
    'aguarde' => 'Aguarde um pouco antes de pedir outro código.',
    'falha' => 'Não foi possível confirmar agora. Tente novamente.',
];

$erro = $mensagens_erro[$_GET['erro'] ?? ''] ?? '';
$falha_envio = isset($_GET['falha_envio']);
$reenviado = isset($_GET['reenviado']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar e-mail | Health Challenges</title>
    <?php include __DIR__ . '/../css/login_style.php'; ?>
</head>

<body class="pagina-login">

    <header class="marca">
        <span class="marca-icone" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
            </svg>
        </span>
        <span class="marca-nome">Health Challenges</span>
        <button type="button" class="btn-daltonismo" data-alternar-daltonismo aria-pressed="false" aria-label="Modo de cores para daltonismo" title="Alternar contraste para daltonismo">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 0 0 20Z" fill="currentColor"/>
            </svg>
            <span>Daltonismo</span>
        </button>
    </header>

    <main class="cartao">

        <section class="lado-mascote" aria-hidden="true">
            <span class="folha folha-1"></span>
            <span class="folha folha-2"></span>

            <img src="../img/mascote.png" alt="" class="mascote">

            <h2>Falta só um passo.</h2>
            <p>Confirmando o e-mail, sua conta fica protegida e pronta para os desafios.</p>
        </section>

        <section class="lado-form">
            <p class="sobretitulo">CONFIRMAÇÃO DE E-MAIL</p>
            <h1>Digite o código</h1>
            <p class="subtitulo">Enviamos um código de 6 números para <?= htmlspecialchars($email_mascarado) ?>. Ele vale por <?= CODIGO_VALIDADE_MINUTOS ?> minutos.</p>

            <?php if ($falha_envio): ?>
                <div class="aviso aviso-erro" role="alert">Não conseguimos enviar o e-mail agora. Peça um novo código abaixo.</div>
            <?php elseif ($reenviado): ?>
                <div class="aviso aviso-sucesso" role="status">Enviamos um novo código. Veja também a caixa de spam.</div>
            <?php endif; ?>

            <?php if ($erro !== ''): ?>
                <div class="aviso aviso-erro" role="alert"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form action="../php/confirmar_codigo.php" method="post">
                <div>
                    <label for="codigo">Código</label>
                    <input type="text" name="codigo" id="codigo" class="campo-codigo"
                           inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                           placeholder="000000" autocomplete="one-time-code" required autofocus>
                </div>

                <button type="submit" class="btn-principal">Confirmar e entrar</button>
            </form>

            <form action="../php/reenviar_codigo.php" method="post" class="form-reenvio">
                <button type="submit" class="btn-reenvio" id="btn-reenvio" data-espera="<?= $espera ?>"<?= $espera > 0 ? ' disabled' : '' ?>>Reenviar código</button>
            </form>

            <p class="link-cadastro">
                <a href="index.php">Voltar para o login</a>
            </p>
        </section>
    </main>

    <script>
        const botaoReenvio = document.getElementById('btn-reenvio');
        let espera = parseInt(botaoReenvio.dataset.espera, 10) || 0;

        function atualizarBotao() {
            if (espera > 0) {
                botaoReenvio.disabled = true;
                botaoReenvio.textContent = 'Reenviar código (' + espera + 's)';
                espera--;
                setTimeout(atualizarBotao, 1000);
            } else {
                botaoReenvio.disabled = false;
                botaoReenvio.textContent = 'Reenviar código';
            }
        }

        atualizarBotao();

        document.getElementById('codigo').addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 6);
        });
    </script>
</body>

</html>
