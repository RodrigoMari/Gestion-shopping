<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario  = $_POST['id_usuario'];

    $resultado = validarUsuario($conn, $id_usuario);

    if ($resultado === true) {
        echo "Usuario validado con éxito.";
    } else {
        echo $resultado;
    }
}
?>