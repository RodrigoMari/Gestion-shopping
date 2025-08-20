<?php
    require_once __DIR__ . '/../config/config.php';
?>

<html>
    <body>
        <style>
            .container {
                display: flex;
                gap: 40px;
            }
            .column {
                flex: 1;
                padding: 20px;
                border: 1px solid #ccc;
                border-radius: 8px;
                background: #f9f9f9;
            }
            .column h2 {
                text-align: center;
            }
            form {
                margin-bottom: 20px;
            }
        </style>
        <div class="container">
            <div class="column">
                <h2>Locales / Usuarios / Promociones</h2>
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
                <form method="post" action="<?= SRC_URL ?>usuarios/validar.php">
                    <label>ID Usuario:</label>
                    <input type="number" name="id_usuario"><br>
                    <button type="submit">Validar Usuario</button>
                </form>
                <form method="post" action="<?= SRC_URL ?>promociones/validar.php">
                    <label>ID Promocion:</label>
                    <input type="number" name="id_promocion"><br>
                    <button type="submit" name="opcion" value="1">Validar promocion</button>
                    <button type="submit" name="opcion" value="0">Denegar promocion</button>
                </form>
            </div>
            <div class="column">
                <h2>Novedades</h2>
                <form method="post" action="<?= SRC_URL ?>novedades/create.php">
                    <label>Texto:</label>
                    <input type="text" name="texto_novedad"><br>
                    <label>Fecha desde:</label>
                    <input type="date" name="fecha_desde"><br>
                    <label>Fecha hasta:</label>
                    <input type="date" name="fecha_hasta"><br>
                    <label>Usuario:</label>
                    <input type="text" name="tipo_usuario"><br>
                    <button type="submit">Crear Novedad</button>
                </form>
                <form method="post" action="<?= SRC_URL ?>novedades/delete.php">
                    <label>ID Novedad:</label>
                    <input type="number" name="id_novedad"><br>
                    <button type="submit">Eliminar Novedad</button>
                </form>
                <form method="post" action="<?= SRC_URL ?>novedades/update.php">
                    <label>ID Novedad:</label>
                    <input type="number" name="id_novedad"><br>
                    <label>texto:</label>
                    <input type="text" name="texto_novedad"><br>
                    <label>fecha desde:</label>
                    <input type="date" name="fecha_desde"><br>
                    <label>fecha hasta:</label>
                    <input type="date" name="fecha_hasta"><br>
                    <label>Usuario:</label>
                    <input type="text" name="tipo_usuario"><br>
                    <button type="submit">Modificar Novedad</button>
                </form>
            </div>
        </div>
    </body>
</html>