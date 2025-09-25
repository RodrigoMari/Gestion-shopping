<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_novedad = $_POST['id_novedad'];

    $resultado = borrarNovedad($conn, $id_novedad);

    if ($resultado === true) {
        setFlashMessage('success', 'Novedad eliminada exitosamente');
        header("Location: " . PUBLIC_URL . "admin/novedades/novedades.php");
        exit();
    } else {
        $mensajeError = $resultado['error'] ?? 'Operación fallida, intente nuevamente';
        setFlashMessage('danger', 'Error al eliminar novedad: ' . $mensajeError);
        header("Location: " . PUBLIC_URL . "admin/novedades/novedades.php");
    }
}
?>