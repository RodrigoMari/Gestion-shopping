<?php
include_once __DIR__ . '/../../config/database.php';

function borrarPromocion($conn, $id_promocion)
{
    $sql1 = "DELETE FROM uso_promociones WHERE codPromo = $id_promocion";
    $conn->query($sql1);

    $sql2 = "DELETE FROM promociones WHERE codPromo = $id_promocion";
    if ($conn->query($sql2) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function modificarPromocion($conn, $id, $texto, $desde, $hasta, $categoria, $dias, $estado, $local) {
    try {
        $sql = "UPDATE promociones 
                SET textoPromo = ?, fechaDesdePromo = ?, fechaHastaPromo = ?, 
                    categoriaCliente = ?, diasSemana = ?, estadoPromo = ?, codLocal = ?
                WHERE codPromo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissi", $texto, $desde, $hasta, $categoria, $dias, $estado, $local, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error;
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function getAllPromociones($conn)
{
    $sql = "SELECT * FROM promociones ORDER BY codPromo ASC";
    $result = $conn->query($sql);

    if ($result) {
        return $result;
    } else {
        return "Error: " . $conn->error;
    }
}

function getPromocionesActivasCount($conn)
{
    $sql = "SELECT COUNT(*) AS total FROM promociones WHERE estadoPromo = 'aprobada' AND fechaHastaPromo >= CURDATE()";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return "Error: " . $conn->error;
    }
}

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

function getPromoById($conn, $id_promocion)
{
    $sql = "SELECT * FROM promociones WHERE codPromo = $id_promocion";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // No se encontró la promoción
        }
    } else {
        return "Error: " . $conn->error;
    }
}
