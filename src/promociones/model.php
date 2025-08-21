<?php
include_once __DIR__ . '/../../config/database.php';

function validarPromocion($conn, $id_promocion, $opcion)
{
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

function getPromocionesDestacadas($conn)
{
    $sql = "SELECT p.textoPromo, p.fechaHastaPromo, l.nombreLocal, p.categoriaCliente,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'aprobada' AND p.fechaHastaPromo >= CURDATE()
            ORDER BY p.fechaHastaPromo ASC
            LIMIT 3";
    return $conn->query($sql);
}
