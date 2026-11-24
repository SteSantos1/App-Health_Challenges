<?php
require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/email_codigo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../html/cadastro.php');
    exit;
}

$nome_digitado = trim($_POST['name'] ?? '');
$email_digitado = trim($_POST['email'] ?? '');
$senha_digitada = $_POST['password'] ?? '';
$confirmacao = $_POST['confirm-password'] ?? '';
$data_digitada = trim($_POST['date-birth'] ?? '');
$altura_texto = trim($_POST['height'] ?? '');
$peso_texto = trim($_POST['weight'] ?? '');

$_SESSION['cadastro_form'] = [
    'name' => $nome_digitado,
    'email' => $email_digitado,
    'date-birth' => $data_digitada,
    'height' => $altura_texto,
    'weight' => $peso_texto,
];

function voltar_cadastro(string $erro): void
{
    header('Location: ../html/cadastro.php?erro=' . $erro, true, 303);
    exit;
}

$altura_digitada = (float) str_replace(',', '.', $altura_texto);
$peso_digitado = (float) str_replace(',', '.', $peso_texto);

if ($nome_digitado === '') {
    voltar_cadastro('nome');
}

if (!filter_var($email_digitado, FILTER_VALIDATE_EMAIL)) {
    voltar_cadastro('email-invalido');
}

if ($senha_digitada === '' || $senha_digitada !== $confirmacao) {
    voltar_cadastro('senhas');
}

$data_nascimento = DateTime::createFromFormat('Y-m-d', $data_digitada);
$data_valida = $data_nascimento && $data_nascimento->format('Y-m-d') === $data_digitada;

if (!$data_valida || $data_nascimento > new DateTime('today')) {
    voltar_cadastro('data');
}

if ($altura_digitada < 0.5 || $altura_digitada > 2.5) {
    voltar_cadastro('altura');
}

if ($peso_digitado < 20 || $peso_digitado > 400) {
    voltar_cadastro('peso');
}

$stmt = $pdo->prepare('SELECT id_usuario, nome, email_verificado FROM usuario WHERE email = :email LIMIT 1');
$stmt->execute([':email' => $email_digitado]);
$existente = $stmt->fetch();

if ($existente) {
    if ((int) $existente['email_verificado'] === 1) {
        voltar_cadastro('email-em-uso');
    }

    $_SESSION['verificar_email_id'] = (int) $existente['id_usuario'];
    unset($_SESSION['cadastro_form']);

    if (segundos_para_reenviar($pdo, (int) $existente['id_usuario']) === 0) {
        enviar_novo_codigo($pdo, (int) $existente['id_usuario'], $email_digitado, $existente['nome']);
    }

    header('Location: ../html/confirmar_email.php?pendente=1', true, 303);
    exit;
}

$senha_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO usuario (nome, email, senha, data_nascimento, altura, peso, email_verificado)
         VALUES (:nome, :email, :senha, :data_nascimento, :altura, :peso, 0)'
    );
    $stmt->execute([
        ':nome' => $nome_digitado,
        ':email' => $email_digitado,
        ':senha' => $senha_hash,
        ':data_nascimento' => $data_digitada,
        ':altura' => $altura_digitada,
        ':peso' => $peso_digitado,
    ]);

    $id_novo_usuario = (int) $pdo->lastInsertId();
    $imc = $peso_digitado / ($altura_digitada * $altura_digitada);

    $stmt = $pdo->prepare(
        'INSERT INTO historico_corporal (altura, peso, imc, data_registro, id_usuario)
         VALUES (:altura, :peso, :imc, CURDATE(), :id_usuario)'
    );
    $stmt->execute([
        ':altura' => $altura_digitada,
        ':peso' => $peso_digitado,
        ':imc' => round($imc, 2),
        ':id_usuario' => $id_novo_usuario,
    ]);

    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());
    voltar_cadastro('falha');
}

unset($_SESSION['cadastro_form']);
$_SESSION['verificar_email_id'] = $id_novo_usuario;

$enviado = enviar_novo_codigo($pdo, $id_novo_usuario, $email_digitado, $nome_digitado);

header('Location: ../html/confirmar_email.php' . ($enviado ? '' : '?falha_envio=1'), true, 303);
exit;