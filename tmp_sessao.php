<?php
session_start();
$_SESSION['id_usuario'] = 1;
$_SESSION['nome'] = 'Teste';
header('Location: inicio.php');
