<?php
require_once __DIR__ . '/../../config/database.php';

function crearNovedad($conn, $texto_novedad, $fecha_desde, $fecha_hasta, $tipo_usuario)
{
    $sql = "INSERT INTO novedades (textoNovedad, fechaDesdeNovedad, fechaHastaNovedad, tipoUsuario) 
            VALUES ('$texto_novedad', '$fecha_desde', '$fecha_hasta', '$tipo_usuario')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function borrarNovedad($conn, $id_novedad)
{
    $sql = "DELETE FROM novedades WHERE codNovedad = $id_novedad";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function modificarNovedad($conn, $id_novedad, $texto_novedad, $fecha_desde, $fecha_hasta, $tipo_usuario)
{
    $sql = "UPDATE novedades SET textoNovedad = '$texto_novedad', fechaDesdeNovedad = '$fecha_desde', fechaHastaNovedad = '$fecha_hasta', tipoUsuario = '$tipo_usuario' WHERE codNovedad = $id_novedad";

    try {
        $conn->query($sql);
        return true;
    } catch (mysqli_sql_exception $e) {
        return ["error" => "Operación fallida, intente nuevamente"];
    }
}

function obtenerNovedadesVigentes($conn, $limite = 2)
{
    $sql = "SELECT textoNovedad, fechaDesdeNovedad, fechaHastaNovedad 
            FROM novedades 
            WHERE fechaDesdeNovedad <= CURDATE() AND fechaHastaNovedad >= CURDATE() 
            ORDER BY fechaDesdeNovedad DESC
            LIMIT $limite";
    $result = $conn->query($sql);
    if ($result) {
        return $result;
    } else {
        return "Error: " . $conn->error;
    }
}

function getAllNovedades($conn, $limit = 10, $offset = 0)
{
    $sql = "SELECT * FROM novedades ORDER BY codNovedad ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

function contarTodasNovedades($conn)
{
    $sql = "SELECT COUNT(*) as total FROM novedades";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getNovedadById($conn, $id_novedad)
{
    $sql = "SELECT * FROM novedades WHERE codNovedad = $id_novedad";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    } else {
        return "Error: " . $conn->error;
    }
}
