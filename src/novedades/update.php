<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_novedad  = $_POST['id_novedad'];
    $texto_novedad    = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $tipo_usuario   = $_POST['tipo_usuario'];

    $resultado = modificarNovedad($conn, $id_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $tipo_usuario);

    if ($resultado === true) {
        setFlashMessage('success', 'Novedad modificada exitosamente');
        header("Location: " . PUBLIC_URL . "admin/novedades/novedades.php");
        exit();
    } else {
        $mensajeError = $resultado['error'] ?? 'Credenciales invalidas';
        setFlashMessage('danger', 'Error al modificar novedad: ' . $mensajeError);
        header("Location: " . PUBLIC_URL . "admin/novedades/edit.php?id=" . $id_novedad);
    }
}
?>