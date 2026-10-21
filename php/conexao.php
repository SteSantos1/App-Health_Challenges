<?php
require_once __DIR__ . '/env.php';

$dsn = "mysql:host=$ENV_HOST;dbname=$ENV_DBNAME;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $ENV_USERNAME, $ENV_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erro de conexão: ' . $e->getMessage();
    exit;
}
?>