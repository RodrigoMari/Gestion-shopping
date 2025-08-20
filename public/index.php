<?php
    require_once __DIR__ . '/../config/config.php';
?>

<html>
    <body>
        <form method="post" action="<?= SRC_URL ?>locales/create.php">
            <label>Nombre:</label>
            <input type="text" name="nombre_local"><br>
            
            <label>Ubicación:</label>
            <input type="text" name="ubicacion"><br>
            
            <label>Rubro:</label>
            <input type="text" name="rubro"><br>
            
            <label>ID Usuario:</label>
            <input type="number" name="id_usuario"><br>

            <button type="submit">Crear Local</button>
        </form>

        <form method="post" action="<?= SRC_URL ?>locales/delete.php">
            <label>ID Local:</label>
            <input type="number" name="id_local"><br>
            <button type="submit">Eliminar Local</button>
        </form>

        <form method="post" action="<?= SRC_URL ?>locales/update.php">
            <label>ID Local:</label>
            <input type="number" name="id_local"><br>

            <label>Nombre:</label>
            <input type="text" name="nombre_local"><br>

            <label>Ubicación:</label>
            <input type="text" name="ubicacion"><br>

            <label>Rubro:</label>
            <input type="text" name="rubro"><br>

            <label>ID Usuario:</label>
            <input type="number" name="id_usuario"><br>

            <button type="submit">Modificar Local</button>
        </form>
    </body>
</html>