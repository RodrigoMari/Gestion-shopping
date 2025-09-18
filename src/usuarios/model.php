<?php
include_once __DIR__ . '/../../config/database.php';

function validarUsuario($conn, $id_usuario) {
    $sql = "UPDATE usuarios SET estado = 'validado' WHERE codUsuario = $id_usuario";

    $resultado = $conn->query($sql);

    if ($resultado) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function getUserById($conn, $id_usuario) {
    $sql = "SELECT * FROM usuarios WHERE codUsuario = $id_usuario";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function getAllUsuarios($conn) {
    $sql = "SELECT * FROM usuarios ORDER BY codUsuario ASC";
    $result = $conn->query($sql);

    if ($result) {
        return $result;
    } else {
        return "Error: " . $conn->error;
    }
}

?>