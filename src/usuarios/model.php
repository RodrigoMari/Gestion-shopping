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

?>