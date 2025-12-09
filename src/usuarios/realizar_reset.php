<?php
session_start();
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../src/helpers/flash.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/autenticacion/recuperar_password.php');
    exit;
}

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$password2 = isset($_POST['password2']) ? $_POST['password2'] : '';

if ($token === '' || $password === '' || $password2 === '') {
    setFlashMessage('danger', 'Datos incompletos.');
    header('Location: ../../public/autenticacion/recuperar_password.php');
    exit;
}

if ($password !== $password2) {
    setFlashMessage('danger', 'Las contraseñas no coinciden.');
    header('Location: ../../public/autenticacion/restablecer.php?token=' . urlencode($token));
    exit;
}

if (strlen($password) < 8) {
    setFlashMessage('warning', 'La contraseña debe tener al menos 8 caracteres.');
    header('Location: ../../public/autenticacion/restablecer.php?token=' . urlencode($token));
    exit;
}

// Verificar usuario por token
$stmt = $conn->prepare('SELECT codUsuario FROM usuarios WHERE token = ?');
$stmt->bind_param('s', $token);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    setFlashMessage('danger', 'Token inválido o vencido.');
    header('Location: ../../public/autenticacion/recuperar_password.php');
    exit;
}

$user = $res->fetch_assoc();
$hash = password_hash($password, PASSWORD_DEFAULT);

// Actualizar contraseña y limpiar token
$upd = $conn->prepare('UPDATE usuarios SET claveUsuario = ?, token = NULL WHERE codUsuario = ?');
$upd->bind_param('si', $hash, $user['codUsuario']);
if ($upd->execute()) {
    setFlashMessage('success', 'Tu contraseña fue actualizada. Ahora puedes iniciar sesión.');
    header('Location: ../../public/autenticacion/login.php');
    exit;
}

setFlashMessage('danger', 'No se pudo actualizar la contraseña. Intenta nuevamente.');
header('Location: ../../public/autenticacion/restablecer.php?token=' . urlencode($token));
exit;
