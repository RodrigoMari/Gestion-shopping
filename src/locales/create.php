<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre   = $_POST['nombre_local'];
    $ubicacion = $_POST['ubicacion'];
    $rubro     = $_POST['rubro'];
    $usuario   = $_POST['id_usuario'];

    $resultado = crearLocal($conn, $nombre, $ubicacion, $rubro, $usuario);

    if ($resultado === true) {
        setFlashMessage('success', 'Local creado exitosamente');
        header("Location: " . PUBLIC_URL . "admin/locales/locales.php");
        exit();
    } else {
        $mensajeError = $resultado['error'] ?? 'Credenciales invalidas';
        setFlashMessage('danger', 'Error al crear local: ' . $mensajeError);
        header("Location: " . PUBLIC_URL . "admin/locales/create.php");
    }
}
?>