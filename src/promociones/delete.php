<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_promocion = $_POST['id_promo'];

    $resultado = borrarPromocion($conn, $id_promocion);

    if ($resultado === true) {
        header("Location: /gestion-shopping/public/admin/promociones/promociones.php");
        exit;
    } else {
        echo $resultado;
    }
}
?>