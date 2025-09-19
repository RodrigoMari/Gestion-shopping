<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_promo       = $_POST['id_promo'];
    $texto_promo    = $_POST['texto_promo'];
    $fecha_desde    = $_POST['fecha_desde'];
    $fecha_hasta    = $_POST['fecha_hasta'];
    $categoria      = $_POST['categoria_cliente'];
    $dias_semana    = $_POST['dias_semana'];
    $estado         = $_POST['estado_promo'];
    $cod_local      = $_POST['cod_local'];

    $resultado = modificarPromocion(
        $conn,
        $id_promo,
        $texto_promo,
        $fecha_desde,
        $fecha_hasta,
        $categoria,
        $dias_semana,
        $estado,
        $cod_local
    );

    if ($resultado === true) {
        header("Location: /gestion-shopping/public/admin/promociones/promociones.php");
        exit;
    } else {
        echo $resultado;
    }
}
?>
