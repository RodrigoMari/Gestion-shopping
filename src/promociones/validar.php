<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_promocion = (int) $_POST['id_promocion'];
    $opcion = $_POST['opcion'];

    $resultado = validarPromocion($conn, $id_promocion, $opcion);

    if ($resultado === true) {
        setFlashMessage('success', 'Promoción aprobada/denegada exitosamente');
        header("Location: " . PUBLIC_URL . "admin/promociones/promociones.php");
        exit();
    } else {
        $mensajeError = $resultado['error'] ?? 'Credenciales invalidas';
        setFlashMessage('danger', 'Error al validar promoción: ' . $mensajeError);
        header("Location: " . PUBLIC_URL . "admin/promociones/promociones.php");
    }
    exit();
}
