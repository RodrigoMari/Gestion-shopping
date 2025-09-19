<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_promocion = (int) $_POST['id_promocion'];
    $opcion = $_POST['opcion'];

    $resultado = validarPromocion($conn, $id_promocion, $opcion);

    if ($resultado) {
        header("Location: ../../public/admin/promociones/promociones.php?msg=ok");
    } else {
        header("Location: ../../public/admin/promociones/promociones.php?msg=error");
    }
    exit();
}
