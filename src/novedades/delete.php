<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_novedad = $_POST['id_novedad'];

    $resultado = borrarNovedad($conn, $id_novedad);

    if ($resultado === true) {
        header("Location: /gestion-shopping/public/admin/novedades/novedades.php");
        exit;
    } else {
        echo $resultado;
    }
}
?>