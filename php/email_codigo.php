<?php
require_once __DIR__ . '/env.php';

const CODIGO_VALIDADE_MINUTOS = 15;
const CODIGO_MAX_TENTATIVAS = 5;
const CODIGO_INTERVALO_REENVIO = 60;

function enviar_email_codigo(string $email, string $nome, string $codigo): bool
{
    global $EMAILJS_SERVICE_ID, $EMAILJS_TEMPLATE_ID, $EMAILJS_PUBLIC_KEY, $EMAILJS_PRIVATE_KEY, $EMAILJS_URL;

    if (empty($EMAILJS_SERVICE_ID) || empty($EMAILJS_TEMPLATE_ID) || empty($EMAILJS_PUBLIC_KEY) || empty($EMAILJS_PRIVATE_KEY)) {
        error_log('EmailJS nao configurado: preencha as variaveis em php/env.local.php');
        return false;
    }

    if (!function_exists('curl_init')) {
        error_log('Extensao curl do PHP nao esta ativa');
        return false;
    }

    $url = !empty($EMAILJS_URL) ? $EMAILJS_URL : 'https://api.emailjs.com/api/v1.0/email/send';

    $corpo = json_encode([
        'service_id' => $EMAILJS_SERVICE_ID,
        'template_id' => $EMAILJS_TEMPLATE_ID,
        'user_id' => $EMAILJS_PUBLIC_KEY,
        'accessToken' => $EMAILJS_PRIVATE_KEY,
        'template_params' => [
            'to_email' => $email,
            'to_name' => $nome,
            'codigo' => $codigo,
        ],
    ]);

    $curl = curl_init($url);
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $corpo,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 15,
    ]);

    $resposta = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $erro_curl = curl_error($curl);
    curl_close($curl);

    if ($resposta === false) {
        error_log('EmailJS: falha na conexao: ' . $erro_curl);
        return false;
    }

    if ($status !== 200) {
        error_log('EmailJS: resposta ' . $status . ': ' . $resposta);
        return false;
    }

    return true;
}

function segundos_para_reenviar(PDO $pdo, int $id_usuario): int
{
    $stmt = $pdo->prepare(
        'SELECT TIMESTAMPDIFF(SECOND, ultimo_envio, NOW()) FROM verificacao_email WHERE id_usuario = :id'
    );
    $stmt->execute([':id' => $id_usuario]);
    $passou = $stmt->fetchColumn();

    if ($passou === false || $passou === null) {
        return 0;
    }

    return max(0, CODIGO_INTERVALO_REENVIO - (int) $passou);
}

function enviar_novo_codigo(PDO $pdo, int $id_usuario, string $email, string $nome): bool
{
    $codigo = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    $hash = password_hash($codigo, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare(
        'INSERT INTO verificacao_email (id_usuario, codigo_hash, expira_em, tentativas, ultimo_envio)
         VALUES (:id, :hash, DATE_ADD(NOW(), INTERVAL ' . CODIGO_VALIDADE_MINUTOS . ' MINUTE), 0, NOW())
         ON DUPLICATE KEY UPDATE
            codigo_hash = VALUES(codigo_hash),
            expira_em = VALUES(expira_em),
            tentativas = 0,
            ultimo_envio = VALUES(ultimo_envio)'
    );
    $stmt->execute([':id' => $id_usuario, ':hash' => $hash]);

    return enviar_email_codigo($email, $nome, $codigo);
}

function mascarar_email(string $email): string
{
    $partes = explode('@', $email, 2);
    if (count($partes) !== 2) {
        return $email;
    }

    $usuario = $partes[0];
    $visivel = mb_substr($usuario, 0, min(2, mb_strlen($usuario)));

    return $visivel . str_repeat('*', max(1, mb_strlen($usuario) - mb_strlen($visivel))) . '@' . $partes[1];
}
