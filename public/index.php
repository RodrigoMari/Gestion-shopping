<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '../../src/novedades/model.php';
require_once __DIR__ . '../../src/promociones/model.php';

$result_promociones = getPromocionesDestacadas($conn);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rosario Center - Ofertas y Promociones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <!-- Hero -->
    <section class="hero-section text-center mb-5 d-flex flex-column align-items-center justify-content-center">
        <h1 class="fw-semibold">Bienvenido a Rosario Center</h1>
        <p class="lead">Descubre las mejores ofertas y promociones de tus locales favoritos.</p>

        <div class="mt-3">
            <a href="#promociones" class="btn btn-warning btn-lg me-2">Ver Promociones</a>
            <a href="registro.php" class="btn btn-light btn-lg">Registrarse</a>
        </div>
    </section>

    <main class="container my-5">
        <!-- Promociones -->
        <section id="promociones" class="promotions-section mb-5">
            <h2 class="text-center mb-4 fs-1 fw-bold">Promociones Destacadas</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($result_promociones as $promo): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= htmlspecialchars($promo['imagenUrl']) ?>"
                                alt="Promoción en <?= htmlspecialchars($promo['nombreLocal']) ?>"
                                class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($promo['textoPromo']) ?></h5>
                                <p class="card-text">Promoción válida en <?= htmlspecialchars($promo['nombreLocal']) ?></p>
                                <p class="card-text">
                                    <small class="text-muted">Válido hasta <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></small>
                                </p>
                                <a href="#" class="btn btn-sm btn-warning">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Novedades -->
        <section class="news-section mb-5">
            <h2 class="text-center mb-4">Novedades del Shopping</h2>
            <div class="row">
                <?php
                $result_novedades = obtenerNovedadesVigentes($conn);
                if ($result_novedades && $result_novedades->num_rows > 0) {
                    while ($row = $result_novedades->fetch_assoc()) {
                        echo '<div class="col-md-6">';
                        echo '  <div class="card shadow-sm">';
                        echo '    <div class="card-body">';
                        echo '      <h5 class="card-title">¡Última Novedad!</h5>';
                        echo '      <p class="card-text">' . htmlspecialchars($row["textoNovedad"]) . '</p>';
                        echo '      <p class="card-text"><small class="text-muted">Desde ' . date("d/m/Y", strtotime($row["fechaDesdeNovedad"])) . ' hasta ' . date("d/m/Y", strtotime($row["fechaHastaNovedad"])) . '</small></p>';
                        echo '    </div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-center col-12">No hay novedades disponibles en este momento.</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <?php include '../includes/contact_form.php'; ?>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>


<!--    <div class="container">
            <div class="column">
                <h2>Locales / Usuarios / Promociones</h2>
                <form method="post" action="<?= SRC_URL ?>locales/delete.php">
                    <label>ID Local:</label>
                    <input type="number" name="id_local"><br>
                    <button type="submit">Eliminar Local</button>
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
        </div>
    </body>
</html> -->