<?php

require_once __DIR__ . '/../php/env.php';

if (isset($_SESSION['id_usuario'])) {
    header('Location: inicio.php');
    exit;
}

$erro = '';
$email_digitado = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email_digitado = trim($_POST['email'] ?? '');
    $senha_digitada = $_POST['password'] ?? '';

    if ($email_digitado === '' || $senha_digitada === '') {
        $erro = 'Preencha o e-mail e a senha.';
    } else {
        $dsn = "mysql:host=$ENV_HOST;dbname=$ENV_DBNAME;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $ENV_USERNAME, $ENV_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Erro de conexão: ' . $e->getMessage();
            exit;
        }

        $sql = "SELECT id_usuario, nome, senha FROM usuario WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email_digitado);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha_digitada, $usuario['senha'])) {

            session_regenerate_id(true);

           
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nome']       = $usuario['nome'];

            header('Location: inicio.php');
            exit;
        }

        $erro = 'E-mail ou senha incorretos.';
    }
}

$saiu = isset($_GET['saiu']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Health Challenges</title>
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
    </header>

    <main class="cartao">

        <section class="lado-mascote" aria-hidden="true">
            <span class="folha folha-1"></span>
            <span class="folha folha-2"></span>

              <img src="../img/mascote.png" alt="" class="mascote">

            <h2>Pequenos passos, grandes conquistas.</h2>
            <p>Crie hábitos saudáveis no seu ritmo e celebre cada avanço.</p>
        </section>

        <section class="lado-form">
            <p class="sobretitulo">BEM-VINDO DE VOLTA</p>
            <h1>Entre na sua conta</h1>
            <p class="subtitulo">Continue evoluindo um desafio por vez.</p>

            <?php if ($saiu): ?>
                <div class="aviso aviso-sucesso" role="status">Você saiu da sua conta.</div>
            <?php endif; ?>

            <?php if ($erro !== ''): ?>
                <div class="aviso aviso-erro" role="alert"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form id="form-login" action="index.php" method="post" novalidate>
                <div>
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email"
                           placeholder="voce@exemplo.com"
                           value="<?= htmlspecialchars($email_digitado) ?>"
                           autocomplete="email" required>
                </div>

                <div>
                    <label for="password">Senha</label>
                    <div class="campo-senha">
                        <input type="password" name="password" id="password"
                               placeholder="Sua senha"
                               autocomplete="current-password" required>
                        <button type="button" id="mostrar-senha" class="btn-olho"
                                aria-label="Mostrar senha">Mostrar</button>
                    </div>
                </div>

                <div id="erro-js" class="aviso aviso-erro escondido" role="alert"></div>

                <button type="submit" class="btn-principal">Entrar</button>
            </form>

            <p class="link-cadastro">
                <a href="cadastro.php">Ainda não tem conta? Criar conta</a>
            </p>
        </section>
    </main>

    <?php include __DIR__ . '/../javascript/login_script.php'; ?>
</body>

</html>
