<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_local  = $_POST['id_local'];
    $nombre    = $_POST['nombre_local'];
    $ubicacion = $_POST['ubicacion'];
    $rubro     = $_POST['rubro'];
    $usuario   = $_POST['id_usuario'];

    $resultado = modificarLocal($conn, $id_local, $nombre, $ubicacion, $rubro, $usuario);

    if ($resultado === true) {
        echo "Local modificado con éxito.";
    } else {
        echo $resultado;
    }
}
?>