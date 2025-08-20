<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_promocion  = $_POST['id_promocion'];
    $opcion = $_POST['opcion'];

    $resultado = validarPromocion($conn, $id_promocion, $opcion);

    if ($resultado === true) {
        echo "Promoción validada con éxito.";
    } else {
        echo $resultado;
    }
}
?>