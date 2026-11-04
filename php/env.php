<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ENV_HOST = 'localhost';
$ENV_DBNAME = 'healthchallenges';
$ENV_USERNAME = 'root';
$ENV_PASSWORD = '';

$arquivo_configuracao_local = __DIR__ . '/env.local.php';
if (file_exists($arquivo_configuracao_local)) {
    require_once $arquivo_configuracao_local;
}

?>