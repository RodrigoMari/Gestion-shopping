<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_novedad  = $_POST['id_novedad'];
    $texto_novedad    = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $tipo_usuario   = $_POST['tipo_usuario'];

    $resultado = modificarNovedad($conn, $id_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $tipo_usuario);

    if ($resultado === true) {
        header("Location: /gestion-shopping/public/admin/novedades/novedades.php");
        exit;
    } else {
        echo $resultado;
    }
}
?>