<?php
require_once __DIR__ . '/../../config/database.php';

function crearLocal($conn, $nombre_local, $ubicacion, $rubro, $id_usuario)
{
    $sql = "INSERT INTO locales (nombreLocal, ubicacionLocal, rubroLocal, codUsuario) 
            VALUES ('$nombre_local', '$ubicacion', '$rubro', $id_usuario)";

    try {
        $conn->query($sql);
        return true;
    } catch (mysqli_sql_exception $e) {
        return ["error" => "Usuario inexistente o no dueño de local"];
    }
}

function borrarLocal($conn, $id_local)
{
    $sql = "DELETE FROM locales WHERE codLocal = $id_local";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function modificarLocal($conn, $id_local, $nombre_local, $ubicacion, $rubro, $id_usuario)
{
    $sql = "UPDATE locales SET nombreLocal = '$nombre_local', ubicacionLocal = '$ubicacion', rubroLocal = '$rubro', codUsuario = $id_usuario WHERE codLocal = $id_local";

    try {
        $conn->query($sql);
        return true;
    } catch (mysqli_sql_exception $e) {
        return ["error" => "Usuario inexistente o no dueño de local"];
    }
}

function getAllLocales($conn, $limit = 10, $offset = 0)
{
    $sql = "SELECT * FROM locales ORDER BY nombreLocal ASC LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

function contarTodosLocales($conn)
{
    $sql = "SELECT COUNT(*) as total FROM locales";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getAllRubros($conn)
{
    $sql = "SELECT DISTINCT rubroLocal FROM locales WHERE rubroLocal IS NOT NULL AND rubroLocal != '' ORDER BY rubroLocal ASC";
    $result = $conn->query($sql);
    $rubros = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rubros[] = $row['rubroLocal'];
        }
    }
    return $rubros;
}

function getLocalesPropietariosCount($conn)
{
    $sql = "SELECT COUNT(DISTINCT codUsuario) AS total FROM locales";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return "Error: " . $conn->error;
    }
}

function getLocalesRubrosCount($conn)
{
    $sql = "SELECT COUNT(DISTINCT rubroLocal) AS total FROM locales";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return "Error: " . $conn->error;
    }
}

function getLocalById($conn, $id_local)
{
    $sql = "SELECT * FROM locales WHERE codLocal = $id_local";
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
