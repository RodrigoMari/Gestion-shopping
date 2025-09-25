<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_local  = $_POST['id_local'];
    $nombre    = $_POST['nombre_local'];
    $ubicacion = $_POST['ubicacion'];
    $rubro     = $_POST['rubro'];
    $usuario   = $_POST['id_usuario'];

    $resultado = modificarLocal($conn, $id_local, $nombre, $ubicacion, $rubro, $usuario);

    if ($resultado === true) {
        setFlashMessage('success', 'Local modificado exitosamente');
        header("Location: " . PUBLIC_URL . "admin/locales/locales.php");
        exit();
    } else {
        $mensajeError = $resultado['error'] ?? 'Credenciales invalidas';
        setFlashMessage('danger', 'Error al modificar local: ' . $mensajeError);
        header("Location: " . PUBLIC_URL . "admin/locales/edit.php?id=" . $id_local);
    }
}
?>