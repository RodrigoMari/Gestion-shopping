<?php
require_once __DIR__ . '/../helpers/flash.php';
require_once __DIR__ . '/model.php';

if (!isset($_SESSION['codUsuario'])) {
    setFlashMessage('warning', 'Debes iniciar sesion para solicitar una promocion.');
    header('Location: ../../public/autenticacion/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['codPromo'])) {
    setFlashMessage('danger', 'Solicitud invalida.');
    header('Location: ../../public/promociones.php');
    exit();
}

$codCliente = (int) $_SESSION['codUsuario'];
$codPromo = (int) $_POST['codPromo'];

$resultado = solicitarPromocion($conn, $codCliente, $codPromo);

if ($resultado['success']) {
    setFlashMessage('success', 'Solicitud enviada correctamente. El local debe aprobarla.');
} else {
    $mensaje = $resultado['error'] ?? 'Ocurrio un error al solicitar la promocion.';
    setFlashMessage('danger', 'No se pudo solicitar la promocion: ' . $mensaje);
}

header('Location: ../../public/promociones.php');
exit();
