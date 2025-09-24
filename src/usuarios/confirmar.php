<?php
require_once __DIR__ . '/../helpers/flash.php';
require_once __DIR__ . '/model.php';

if (!isset($_GET['token'])) {
    setFlashMessage('danger', 'Token invalido.');
    header('Location: ../../public/autenticacion/login.php');
    exit();
}

$token = $_GET['token'];

if (validarUsuarioPorToken($conn, $token)) {
    setFlashMessage('success', 'Cuenta validada correctamente. Ya puedes iniciar sesion.');
} else {
    setFlashMessage('danger', 'Token invalido o ya usado.');
}

header('Location: ../../public/autenticacion/login.php');
exit();
