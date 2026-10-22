<?php
require_once __DIR__ . '/env.php';

$dsn = "mysql:host=$ENV_HOST;dbname=$ENV_DBNAME";

try {
    $pdo = new PDO($dsn, $ENV_USERNAME, $ENV_PASSWORD);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro de conexão: ' . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $nome_digitado = trim($_POST['name']);
    $email_digitado = trim($_POST['email']);
    $senha_digitada = trim($_POST['password']);
    $data_digitada = trim($_POST['date-birth']);
    $altura_digitada = (float) str_replace(',', '.', trim($_POST['height']));
    $peso_digitado = (float) str_replace(',', '.', trim($_POST['weight']));

    if ($altura_digitada < 0.5 || $altura_digitada > 2.5) {
        echo 'Altura inválida. Informe um valor entre 0,5 e 2,5 metros.';
        exit;
    }

    if ($peso_digitado < 20 || $peso_digitado > 400) {
        echo 'Peso inválido. Informe um valor entre 20 e 400 kg.';
        exit;
    }

    $sql = "SELECT id_usuario FROM usuario WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email_digitado);
    $stmt->execute();

    if ($stmt->fetch()) {
        echo 'Este email já está cadastrado.';
        exit;
    }

    $senha_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuario (nome, email, senha, data_nascimento, altura, peso) VALUES (:nome, :email, :senha, :data_nascimento, :altura, :peso)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome_digitado);
    $stmt->bindParam(':email', $email_digitado);
    $stmt->bindParam(':senha', $senha_hash);
    $stmt->bindParam(':data_nascimento', $data_digitada);
    $stmt->bindParam(':altura', $altura_digitada);
    $stmt->bindParam(':peso', $peso_digitado);

    $stmt->execute();
    header('Location: ../html/inicio.php');
    exit;
}

?>