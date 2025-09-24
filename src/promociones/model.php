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

function getAllPromociones($conn)
{
    $sql = "SELECT p.*, l.nombreLocal 
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            ORDER BY p.fechaDesdePromo DESC";
    return $conn->query($sql);
}

function getPromocionesPendientes($conn)
{
    $sql = "SELECT p.*, l.nombreLocal
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'pendiente'";
    return $conn->query($sql);
}


function getPromocionesPorNivel($conn, $categoriaCliente = null)
{
    $sqlBase = "SELECT p.codPromo, p.textoPromo, p.fechaDesdePromo, p.fechaHastaPromo, 
                   p.categoriaCliente, p.diasSemana,
                   l.nombreLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'aprobada'";

    if ($categoriaCliente === null) {
        $sql = $sqlBase;
        $stmt = $conn->prepare($sql);
    } else {
        $niveles = [];
        switch ($categoriaCliente) {
            case 'Inicial':
                $niveles = ['Inicial'];
                break;
            case 'Medium':
                $niveles = ['Inicial', 'Medium'];
                break;
            case 'Premium':
                $niveles = ['Inicial', 'Medium', 'Premium'];
                break;
        }

        $placeholders = implode(',', array_fill(0, count($niveles), '?'));
        $sql = $sqlBase . " AND (p.categoriaCliente IN ($placeholders) OR p.categoriaCliente IS NULL)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($niveles)), ...$niveles);
    }

    $stmt->execute();
    return $stmt->get_result();
}

function solicitarPromocion($conn, $codCliente, $codPromo)
{
    $fechaHoy = date('Y-m-d');

    $sqlCheck = "SELECT * FROM uso_promociones WHERE codCliente=? AND codPromo=? AND fechaUsoPromo=?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("iis", $codCliente, $codPromo, $fechaHoy);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return ["success" => false, "error" => "Ya solicitaste esta promocion hoy."];
    }

    $sql = "INSERT INTO uso_promociones (codCliente, codPromo, fechaUsoPromo, estado) 
            VALUES (?, ?, ?, 'enviada')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $codCliente, $codPromo, $fechaHoy);

    if ($stmt->execute()) {
        return ["success" => true];
    } else {
        return ["success" => false, "error" => $conn->error];
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
    if ($opcion === "aprobar") {
        $sql = "UPDATE promociones SET estadoPromo = 'aprobada' WHERE codPromo = ?";
    } else {
        $sql = "UPDATE promociones SET estadoPromo = 'denegada' WHERE codPromo = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_promocion);
    return $stmt->execute();
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

function getPromocionesPorLocal($conn, $codLocal)
{
    $sql = "SELECT p.*, l.nombreLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.codLocal = ? AND p.estadoPromo = 'aprobada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codLocal);
    $stmt->execute();
    return $stmt->get_result();
}

// Duenos locales

function getPromoById($conn, $codPromo)
{
    $sql = "SELECT p.*, l.nombreLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.codPromo = ? AND p.estadoPromo = 'aprobada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codPromo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function contarUsoPromocion($conn, $codPromo)
{
    $sql = "SELECT COUNT(*) as total FROM uso_promociones WHERE codPromo=? AND estado='aceptada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codPromo);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

function getSolicitudesPendientes($conn, $codLocal)
{
    $sql = "SELECT u.codCliente, u.codPromo, u.fechaUsoPromo, us.nombreUsuario, p.textoPromo
            FROM uso_promociones u
            JOIN usuarios us ON u.codCliente = us.codUsuario
            JOIN promociones p ON u.codPromo = p.codPromo
            WHERE p.codLocal=? AND u.estado IN ('pendiente','enviada')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codLocal);
    $stmt->execute();
    return $stmt->get_result();
}

// Cliente
function getSolicitudesPorCliente($conn, $codCliente)
{
    $sql = "SELECT u.estado AS estadoSolicitud, u.fechaUsoPromo, 
                   p.textoPromo, l.nombreLocal
            FROM uso_promociones u
            JOIN promociones p ON u.codPromo = p.codPromo
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE u.codCliente = ?
            ORDER BY u.fechaUsoPromo DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codCliente);
    $stmt->execute();
    return $stmt->get_result();
}

function contarPromosUsadasPorCliente($conn, $codCliente)
{
    $sql = "SELECT COUNT(*) as total 
            FROM uso_promociones 
            WHERE codCliente = ? AND estado = 'aceptada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codCliente);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}
