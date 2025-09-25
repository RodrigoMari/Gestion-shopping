<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto_novedad   = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $resultado = crearNovedad($conn, $texto_novedad, $fecha_desde, $fecha_hasta, $tipo_usuario);

    if ($resultado === true) {
        setFlashMessage('success', 'Novedad creada exitosamente');
        header("Location: " . PUBLIC_URL . "admin/novedades/novedades.php");
        exit();
    } else {
        $mensajeError = $resultado['error'] ?? 'Credenciales invalidas';
        setFlashMessage('danger', 'Error al crear novedad: ' . $mensajeError);
        header("Location: " . PUBLIC_URL . "admin/novedades/create.php");
    }
}
?>