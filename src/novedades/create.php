<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto_novedad   = $_POST['texto_novedad'];
    $fecha_desde = $_POST['fecha_desde'];
    $fecha_hasta = $_POST['fecha_hasta'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $resultado = crearNovedad($conn, $texto_novedad, $fecha_desde, $fecha_hasta, $tipo_usuario);

    if ($resultado === true) {
        echo "Novedad creada con éxito.";
    } else {
        echo $resultado;
    }
}
?>