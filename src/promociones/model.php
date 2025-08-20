<?php
include_once __DIR__ . '/../../config/database.php';

function validarPromocion($conn, $id_promocion, $opcion) {
    if ($opcion == 1) {
        $sql = "UPDATE promociones SET estadoPromo = 'aprobada' WHERE codPromo = $id_promocion";
    } else {
        $sql = "UPDATE promociones SET estadoPromo = 'denegada' WHERE codPromo = $id_promocion";
    }

    $resultado = $conn->query($sql);

    if ($resultado) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

?>