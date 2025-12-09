<?php
session_start();
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/config.php';
include_once __DIR__ . '/../../src/helpers/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/autenticacion/recuperar_password.php');
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if ($email === '') {
    setFlashMessage('danger', 'Debes ingresar un correo.');
    header('Location: ../../public/autenticacion/recuperar_password.php');
    exit;
}

// Buscar usuario por email
$stmt = $conn->prepare('SELECT codUsuario, estado FROM usuarios WHERE nombreUsuario = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    // No revelar que no existe: respuesta genérica
    setFlashMessage('info', 'Si el correo existe, te mostraremos el enlace para restablecer.');
    header('Location: ../../public/autenticacion/recuperar_password.php');
    exit;
}

$user = $res->fetch_assoc();

// Generar token y guardarlo en la columna `token`
$token = bin2hex(random_bytes(16));
$update = $conn->prepare('UPDATE usuarios SET token = ? WHERE codUsuario = ?');
$update->bind_param('si', $token, $user['codUsuario']);
$update->execute();

// Construir enlace con la constante PUBLIC_URL
$resetLink = rtrim(PUBLIC_URL, '/') . '/autenticacion/restablecer.php?token=' . urlencode($token);

// Enviar por correo electrónico (simple usando mail()).
$subject = 'Restablecer tu contraseña - Rosario Center';
$message = "Hola,\n\nRecibimos una solicitud para restablecer tu contraseña.\n\nPara continuar, abre este enlace:\n$resetLink\n\nSi no fuiste vos, podés ignorar este email.\n\nSaludos,\nRosario Center";
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/plain; charset=UTF-8';
$headers[] = 'From: Rosario Center <no-reply@rosariocenter.local>';
$headers[] = 'X-Mailer: PHP/' . phpversion();
$headersStr = implode("\r\n", $headers);

$mailOk = @mail($email, $subject, $message, $headersStr);

if ($mailOk) {
    setFlashMessage('success', 'Si el correo existe, te enviamos un enlace para restablecer la contraseña.');
} else {
    // Fallback: no revelar detalles, pero informar error genérico
    setFlashMessage('warning', 'No pudimos enviar el correo en este momento. Intenta nuevamente más tarde.');
}

header('Location: ../../public/autenticacion/recuperar_password.php');
exit;
