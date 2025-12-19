<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/promociones/model.php';
require_once __DIR__ . '/../src/locales/model.php';

$rubrosDisponibles = getAllRubros($conn);

$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$porPagina = 12;
$offset = ($pagina - 1) * $porPagina;

$categoria = isset($_SESSION['categoriaCliente']) ? $_SESSION['categoriaCliente'] : null;
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$rubroSeleccionado = isset($_GET['rubro']) ? $_GET['rubro'] : '';
$codLocal = isset($_GET['codLocal']) && $_GET['codLocal'] !== '' ? (int) $_GET['codLocal'] : null;

if (empty($busqueda) && $codLocal) {
  $localData = getLocalById($conn, $codLocal);
  if ($localData) {
    $busqueda = $localData['nombreLocal'];
  }
}

$hasFilters = ($busqueda !== '' || $rubroSeleccionado !== '' || $codLocal !== null);

if ($codLocal) {
  $result_promociones = getPromocionesPorLocal($conn, $codLocal, $porPagina, $offset);
  $totalRegistros = contarPromocionesPorLocal($conn, $codLocal);
} else {
  $result_promociones = filtrarPromociones($conn, $busqueda, $rubroSeleccionado, $categoria, $porPagina, $offset);
  $totalRegistros = contarPromocionesFiltradas($conn, $busqueda, $rubroSeleccionado, $categoria);
}

$totalPaginas = ceil($totalRegistros / $porPagina);
$promociones = [];

if ($result_promociones instanceof mysqli_result) {
  while ($row = $result_promociones->fetch_assoc()) {
    $promociones[] = $row;
  }
  $result_promociones->free();
}

function getPaginationLink($page, $busqueda, $rubro, $codLocal)
{
  $params = ['pagina' => $page];
  if ($busqueda) $params['buscar'] = $busqueda;
  if ($rubro) $params['rubro'] = $rubro;
  if ($codLocal) $params['codLocal'] = $codLocal;
  return 'promociones.php?' . http_build_query($params);
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
  <?php include '../includes/flash_toast.php'; ?>
  <?php include '../includes/header.php'; ?>

  <main class="container my-5">

    <h2 class="text-center mb-4 fs-1 fw-bold">Todas las Promociones</h2>

    <div class="card shadow-sm mb-5 border-0 bg-light">
      <div class="card-body p-4">
        <form class="row g-3 justify-content-center align-items-end" method="GET" action="promociones.php">
          <div class="col-12 col-md-5">
            <label for="buscar" class="form-label fw-semibold">Buscar</label>
            <div class="input-group">
              <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
              <input type="text" class="form-control border-start-0" id="buscar" name="buscar"
                placeholder="Nombre del local o promoción..."
                value="<?= htmlspecialchars($busqueda) ?>">
            </div>
          </div>

          <div class="col-12 col-md-4">
            <label for="rubro" class="form-label fw-semibold">Filtrar por Rubro</label>
            <select class="form-select" name="rubro" id="rubro">
              <option value="">Todos los rubros</option>
              <?php foreach ($rubrosDisponibles as $r): ?>
                <option value="<?= htmlspecialchars($r) ?>" <?= ($rubroSeleccionado === $r) ? 'selected' : '' ?>>
                  <?= ucfirst(htmlspecialchars($r)) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-warning w-100 fw-bold">
              <i class="fas fa-filter me-1"></i> Filtrar
            </button>
            <?php if ($hasFilters): ?>
              <a href="promociones.php" class="btn btn-outline-secondary w-auto" title="Limpiar filtros">
                <i class="fas fa-times"></i>
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <?php if (!empty($promociones)): ?>
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($promociones as $promo): ?>
          <div class="col">
            <div class="card h-100 shadow-sm border-0">
              <div class="position-absolute top-0 end-0 m-2">
                <span class="badge bg-warning text-dark shadow-sm">
                  <?= htmlspecialchars($promo['rubroLocal'] ?? 'General') ?>
                </span>
              </div>

              <img src="<?= htmlspecialchars($promo['imagenUrl']) ?>" alt="Promoción en <?= htmlspecialchars($promo['nombreLocal']) ?>" class="card-img-top" loading="lazy">

              <div class="card-body d-flex flex-column">
                <h5 class="card-title text-primary fw-bold"><?= htmlspecialchars($promo['textoPromo']) ?></h5>
                <p class="card-text text-muted mb-1">
                  <i class="fas fa-store me-1"></i> <?= htmlspecialchars($promo['nombreLocal']) ?>
                </p>

                <?php if (!empty($promo['diasSemana'])):
                  $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                  $diaNombre = $dias[$promo['diasSemana']] ?? 'Día específico';
                ?>
                  <p class="card-text small text-muted mb-1">
                    <i class="fas fa-calendar-day me-1"></i>Válido los <?= $diaNombre ?>
                  </p>
                <?php endif; ?>

                <p class="card-text mb-3">
                  <?php if (!empty($promo['proximamente']) && $promo['proximamente'] == 1): ?>
                    <small class="text-info fw-semibold"><i class="fas fa-clock me-1"></i>Disponible desde <?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?></small>
                  <?php else: ?>
                    <small class="text-muted">Vigente hasta <?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></small>
                  <?php endif; ?>
                </p>

                <div class="mt-auto">
                  <?php if (!empty($promo['proximamente']) && $promo['proximamente'] == 1): ?>
                    <button type="button" class="btn btn-secondary w-100 fw-bold" disabled>
                      <i class="fas fa-hourglass-start me-1"></i>Disponible próximamente
                    </button>
                  <?php elseif (isset($_SESSION['tipoUsuario']) && $_SESSION['tipoUsuario'] === 'cliente'): ?>
                    <form method="POST" action="<?= SRC_URL ?>promociones/solicitar.php">
                      <input type="hidden" name="codPromo" value="<?= $promo['codPromo'] ?>">
                      <button type="submit" class="btn btn-warning w-100 fw-bold">Solicitar Promoción</button>
                    </form>
                  <?php else: ?>
                    <a href="autenticacion/login.php" class="btn btn-outline-warning w-100">Inicia sesión para solicitar</a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <?php if ($totalPaginas > 1): ?>
        <nav aria-label="Navegación de páginas" class="mt-5">
          <ul class="pagination justify-content-center">

            <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= getPaginationLink($pagina - 1, $busqueda, $rubroSeleccionado, $codLocal) ?>" aria-label="Anterior">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
              <li class="page-item <?= ($pagina == $i) ? 'active' : '' ?>">
                <a class="page-link" href="<?= getPaginationLink($i, $busqueda, $rubroSeleccionado, $codLocal) ?>">
                  <?= $i ?>
                </a>
              </li>
            <?php endfor; ?>

            <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
              <a class="page-link" href="<?= getPaginationLink($pagina + 1, $busqueda, $rubroSeleccionado, $codLocal) ?>" aria-label="Siguiente">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>

          </ul>
        </nav>
      <?php endif; ?>
    <?php else: ?>
      <div class="alert alert-info text-center py-5" role="alert">
        <i class="fas fa-search fa-3x mb-3 text-info"></i>
        <h4>No encontramos resultados</h4>
        <p>Intenta cambiar los filtros de búsqueda o el rubro seleccionado.</p>
        <?php if ($hasFilters): ?>
          <a href="promociones.php" class="btn btn-primary mt-2">Ver todas las promociones</a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </main>

  <?php include '../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>