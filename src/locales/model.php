<?php
require_once __DIR__ . '/../../config/database.php';

function crearLocal($conn, $nombre_local, $ubicacion, $rubro, $id_usuario) {
    $sql = "INSERT INTO locales (nombreLocal, ubicacionLocal, rubroLocal, codUsuario) 
            VALUES ('$nombre_local', '$ubicacion', '$rubro', $id_usuario)";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function borrarLocal($conn, $id_local) {
    $sql = "DELETE FROM locales WHERE codLocal = $id_local";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function modificarLocal($conn, $id_local, $nombre_local, $ubicacion, $rubro, $id_usuario) {
    $sql = "UPDATE locales SET nombreLocal = '$nombre_local', ubicacionLocal = '$ubicacion', rubroLocal = '$rubro', codUsuario = $id_usuario WHERE codLocal = $id_local";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

?>