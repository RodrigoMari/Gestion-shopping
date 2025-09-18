<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST['nombre_local'];
    $ubicacion = $_POST['ubicacion'];
    $rubro     = $_POST['rubro'];
    $usuario   = $_POST['id_usuario'];

    $resultado = crearLocal($conn, $nombre, $ubicacion, $rubro, $usuario);

    if ($resultado === true) {
        header("Location: " . PUBLIC_URL . "admin/locales/locales.php");
        exit();
    } else {
        echo $resultado;
    }
}
?>