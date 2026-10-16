<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];
session_destroy();

header('Location: ../html/index.php?saiu=1');
exit;
