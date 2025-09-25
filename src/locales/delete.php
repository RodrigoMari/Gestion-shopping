<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_local = $_POST['id_local'];

    $resultado = borrarLocal($conn, $id_local);

    if ($resultado === true) {
        setFlashMessage('success', 'Local eliminado exitosamente');
        header("Location: " . PUBLIC_URL . "admin/locales/locales.php");
        exit();
    } else {
        $mensajeError = $resultado['error'] ?? 'Operación fallida, intente nuevamente';
        setFlashMessage('danger', 'Error al eliminar local: ' . $mensajeError);
        header("Location: " . PUBLIC_URL . "admin/locales/locales.php");
    }
}
?>