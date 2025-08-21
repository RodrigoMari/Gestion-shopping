<?php
require_once __DIR__ . '/../../config/database.php';

function crearLocal($conn, $nombre_local, $ubicacion, $rubro, $id_usuario)
{
    $sql = "INSERT INTO locales (nombreLocal, ubicacionLocal, rubroLocal, codUsuario) 
            VALUES ('$nombre_local', '$ubicacion', '$rubro', $id_usuario)";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
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

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function getAllLocales($conn)
{
    $sql = "SELECT * FROM locales ORDER BY nombreLocal ASC";
    $result = $conn->query($sql);

    if ($result) {
        return $result;
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
