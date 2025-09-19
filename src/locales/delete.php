<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_local = $_POST['id_local'];

    $resultado = borrarLocal($conn, $id_local);

    if ($resultado === true) {
        header("Location: /gestion-shopping/public/admin/locales/locales.php");
    } else {
        echo $resultado;
    }
}
?>