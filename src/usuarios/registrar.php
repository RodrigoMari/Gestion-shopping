<?php
require_once __DIR__ . '/../helpers/flash.php';
require_once __DIR__ . '/model.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/autenticacion/registro.php');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$tipoUsuario = $_POST['tipoUsuario'] ?? '';

$tiposPermitidos = ['cliente', 'dueno de local'];

if ($email === '' || $password === '' || $confirmPassword === '' || !in_array($tipoUsuario, $tiposPermitidos, true)) {
    setFlashMessage('danger', 'Datos incompletos o tipo de usuario invalido.');
    header('Location: ../../public/autenticacion/registro.php');
    exit();
}

if ($password !== $confirmPassword) {
    setFlashMessage('danger', 'Las contraseñas no coinciden.');
    header('Location: ../../public/autenticacion/registro.php');
    exit();
}

$resultado = registrarUsuario($conn, $email, $password, $tipoUsuario);

if ($resultado['success']) {
    $token = $resultado['token'];
    $link = 'http://localhost/gestion-shopping/src/usuarios/confirmar.php?token=' . urlencode($token);

    $asunto = 'Confirmacion de cuenta - Shopping Rosario';
    $mensaje = "Hola, gracias por registrarte.

Valida tu cuenta aqui:
$link";
    $headers = "From: no-reply@shopping.com\r\n";

    if ($tipoUsuario === 'cliente') {
        if (@mail($email, $asunto, $mensaje, $headers)) {
            setFlashMessage('success', 'Registro exitoso. Revisa tu correo para validar la cuenta.');
        } else {
            setFlashMessage('warning', 'Usuario creado, pero no se pudo enviar el correo de validacion.');
        }
    } else {
        setFlashMessage('success', 'Registro exitoso. Un administrador debe aprobar tu cuenta.');
    }
} else {
    setFlashMessage('danger', 'Error: ' . $resultado['error']);
}

header('Location: ../../public/autenticacion/registro.php');
exit();


