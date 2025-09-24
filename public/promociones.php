<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/promociones/model.php';

$categoria = isset($_SESSION['categoriaCliente']) ? $_SESSION['categoriaCliente'] : null;
$codLocal = isset($_GET['codLocal']) && $_GET['codLocal'] !== '' ? (int) $_GET['codLocal'] : null;
$codPromo = isset($_GET['codPromo']) && $_GET['codPromo'] !== '' ? (int) $_GET['codPromo'] : null;

if ($codPromo) {
  $promo = getPromoById($conn, $codPromo);
  if ($promo) {
    $result_promociones = [$promo];
  } else {
    $result_promociones = [];
  }
} elseif ($codLocal) {
  $result_promociones = getPromocionesPorLocal($conn, $codLocal);
} else {
  $result_promociones = getPromocionesPorNivel($conn, $categoria);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rosario Center - Promociones</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <?php include __DIR__ . '/../includes/flash_toast.php'; ?>

  <?php include '../includes/header.php'; ?>

  <main class="container my-5">

    <h2 class="text-center mb-4 fs-1 fw-bold">Todas las Promociones</h2>

    <form class="row g-3 mb-5 justify-content-center" method="GET" action="promociones.php">
      <div class="col-auto">
        <input type="number" class="form-control" name="codLocal" placeholder="Codigo del local" value="<?= htmlspecialchars($codLocal ?? '') ?>">
      </div>
      <div class="col-auto">
        <input type="number" class="form-control" name="codPromo" placeholder="Codigo de promocion" value="<?= htmlspecialchars($codPromo ?? '') ?>">
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-warning">Buscar</button>
      </div>
    </form>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <?php foreach ($result_promociones as $promo): ?>
        <div class="col">
          <div class="card h-100 shadow-sm">
            <img src="<?= htmlspecialchars($promo['imagenUrl']) ?>"
              alt="Promocion en <?= htmlspecialchars($promo['nombreLocal']) ?>"
              class="card-img-top">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($promo['textoPromo']) ?></h5>
              <p class="card-text">Promocion valida en <?= htmlspecialchars($promo['nombreLocal']) ?></p>
              <p class="card-text">
                <small class="text-muted">Valido hasta <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></small>
              </p>
              <?php if (isset($_SESSION['tipoUsuario']) && $_SESSION['tipoUsuario'] === 'cliente'): ?>
                <form method="POST" action="<?= SRC_URL ?>promociones/solicitar.php">
                  <input type="hidden" name="codPromo" value="<?= $promo['codPromo'] ?>">
                  <button type="submit" class="btn btn-sm btn-warning">Solicitar Promocion</button>
                </form>
              <?php else: ?>
                <a href="autenticacion/login.php" class="btn btn-sm btn-warning">Inicia sesion para solicitar</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <?php include '../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>