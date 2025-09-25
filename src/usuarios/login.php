<?php
require_once __DIR__ . '/../helpers/flash.php';
require_once __DIR__ . '/model.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../public/autenticacion/login.php');
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$resultado = loginUsuario($conn, $email, $password);

if ($resultado['success']) {

    switch ($_SESSION['tipoUsuario']) {
        case 'administrador':
            header('Location: ../../public/admin/dashboard.php');
            setFlashMessage('success', 'Login exitoso');
            break;
        case 'dueno de local':
            header('Location: ../../public/locales/index.php');
            setFlashMessage('success', 'Login exitoso');
            break;
        case 'cliente':
            header('Location: ../../public/index.php');
            setFlashMessage('success', 'Login exitoso');
            break;
    }
    exit();
}

$mensajeError = $resultado['error'] ?? 'Credenciales invalidas.';
setFlashMessage('danger', 'Error al iniciar sesion: ' . $mensajeError);
header('Location: ../../public/autenticacion/login.php');
exit();
