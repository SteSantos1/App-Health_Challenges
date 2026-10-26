<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ENV_HOST = 'localhost';
$ENV_DBNAME = 'healthchallenges';
$ENV_USERNAME = 'root';
$ENV_PASSWORD = '';

?>