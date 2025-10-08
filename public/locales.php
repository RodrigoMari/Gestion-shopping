<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/locales/model.php';
require_once __DIR__ . '/../src/promociones/model.php';

$localesResult = getAllLocales($conn);
$locales = [];
$errorLocales = null;

if ($localesResult instanceof mysqli_result) {
  while ($row = $localesResult->fetch_assoc()) {
    $locales[] = $row;
  }
  $localesResult->free();
} else {
  $errorLocales = $localesResult;
}

$promocionesPorLocal = [];
$promocionesQuery = $conn->query("SELECT codPromo, textoPromo, codLocal, fechaHastaPromo FROM promociones WHERE estadoPromo = 'aprobada' AND fechaHastaPromo >= CURDATE()");
if ($promocionesQuery instanceof mysqli_result) {
  while ($promo = $promocionesQuery->fetch_assoc()) {
    $promocionesPorLocal[$promo['codLocal']][] = $promo;
  }
  $promocionesQuery->free();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Locales - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style.css">
</head>

<body>
  <?php include '../includes/flash_toast.php'; ?>
  <?php include '../includes/header.php'; ?>

  <main class="container my-5">
    <section class="text-center mb-5">
      <h1 class="fw-bold">Nuestros Locales</h1>
      <p class="lead text-muted">Descubri todos los locales asociados al shopping y encontra sus promociones vigentes.</p>
    </section>

    <?php if ($errorLocales): ?>
      <div class="alert alert-danger" role="alert">
        Ocurrio un problema al cargar los locales. Intenta nuevamente mas tarde.
      </div>
    <?php elseif (empty($locales)): ?>
      <div class="text-center py-5">
        <i class="fas fa-store fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">Todavia no hay locales cargados.</h5>
        <p class="text-muted">Vuelve pronto para descubrir las nuevas propuestas del shopping.</p>
      </div>
    <?php else: ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($locales as $local): ?>
          <?php
          $placeholderImage = 'https://placehold.co/600x400?text=' . urlencode($local['nombreLocal']);
          $imageUrl = $placeholderImage;
          if (isset($local['imagenLocal']) && $local['imagenLocal'] !== '') {
            $imageUrl = $local['imagenLocal'];
          } elseif (isset($local['imagenUrl']) && $local['imagenUrl'] !== '') {
            $imageUrl = $local['imagenUrl'];
          }

          $promos = $promocionesPorLocal[$local['codLocal']] ?? [];
          $promosPreview = array_slice($promos, 0, 2);
          ?>
          <div class="col">
            <div class="card h-100 shadow-sm">
              <img src="<?= htmlspecialchars($imageUrl) ?>" alt="Local <?= htmlspecialchars($local['nombreLocal']) ?>" class="card-img-top" loading="lazy">
              <div class="card-body d-flex flex-column">
                <div class="mb-3">
                  <span class="badge bg-warning text-dark mb-2"><?= htmlspecialchars($local['rubroLocal'] ?? 'Local') ?></span>
                  <h5 class="card-title mb-1"><?= htmlspecialchars($local['nombreLocal']) ?></h5>
                  <p class="card-text text-muted mb-0">
                    <i class="fas fa-map-marker-alt me-2 text-warning"></i><?= htmlspecialchars($local['ubicacionLocal'] ?? 'Ubicacion no disponible') ?>
                  </p>
                </div>

                <div class="flex-grow-1">
                  <?php if (!empty($promosPreview)): ?>
                    <p class="fw-semibold text-muted small mb-2">Promociones destacadas:</p>
                    <ul class="list-unstyled small mb-3">
                      <?php foreach ($promosPreview as $promo): ?>
                        <li class="mb-2">
                          <i class="fas fa-tag text-warning me-2"></i>
                          <?= htmlspecialchars($promo['textoPromo']) ?>
                          <?php if (!empty($promo['fechaHastaPromo'])): ?>
                            <br><small class="text-muted">Valida hasta <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></small>
                          <?php endif; ?>
                        </li>
                      <?php endforeach; ?>
                      <?php if (count($promos) > count($promosPreview)): ?>
                        <li class="text-muted">Y <?= count($promos) - count($promosPreview) ?> promociones mas disponibles.</li>
                      <?php endif; ?>
                    </ul>
                  <?php else: ?>
                    <p class="text-muted mb-3">No hay promociones activas en este momento. Vuelve a consultarnos pronto.</p>
                  <?php endif; ?>
                </div>

                <div class="mt-auto">
                  <a href="<?= PUBLIC_URL ?>promociones.php?codLocal=<?= (int) $local['codLocal'] ?>" class="btn btn-warning w-100">Ver promociones</a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <?php include '../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>