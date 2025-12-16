<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '/../../../src/promociones/model.php';

$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$porPagina = 10;
$offset = ($pagina - 1) * $porPagina;

$promociones = getAllPromociones($conn, $porPagina, $offset);
$totalRegistros = contarTodasPromociones($conn);
$totalPaginas = ceil($totalRegistros / $porPagina);

$promosActivasCount = getPromocionesActivasCount($conn);

$diasSemanaMap = [
  0 => 'Domingo',
  1 => 'Lunes',
  2 => 'Martes',
  3 => 'Miércoles',
  4 => 'Jueves',
  5 => 'Viernes',
  6 => 'Sábado'
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Promociones - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>
  <div class="d-flex admin-layout">
    <?php include '../../../includes/flash_toast.php'; ?>
    <?php include '../../../includes/sidebar.php'; ?>
    <div class="flex-grow-1 admin-content">
      <?php include '../../../includes/admin_header.php'; ?>
      <main class="container-fluid my-4 my-md-5 px-2 px-md-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>admin/dashboard.php" class="text-decoration-none">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Promociones</li>
          </ol>
        </nav>

        <!-- Título y botón -->
        <div class="row mb-4">
          <div class="col-12 col-md-8">
            <h2 class="fw-bold text-dark">Administración de Promociones</h2>
            <p class="text-muted">Aprueba o deniega las promociones creadas por los dueños de locales.</p>
          </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-5">
          <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
              <div class="card-body">
                <i class="fas fa-tags fa-2x text-info mb-2"></i>
                <h4 class="fw-bold">
                  <?php
                  if (is_object($promociones)) {
                    echo $promociones->num_rows;
                    $promociones->data_seek(0);
                  } else {
                    echo '0';
                  }
                  ?>
                </h4>
                <p class="text-muted mb-0">Promociones Totales</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
              <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h4 class="fw-bold"><?= $promosActivasCount ?></h4>
                <p class="text-muted mb-0">Promociones Vigentes</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla -->
        <div class="card shadow-sm border-0">
          <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">Lista de Promociones</h5>
          </div>
          <div class="card-body p-0">
            <?php if (is_object($promociones) && $promociones->num_rows > 0): ?>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="fw-semibold">ID</th>
                      <th class="fw-semibold">Promoción</th>
                      <th class="fw-semibold">Local</th>
                      <th class="fw-semibold text-nowrap">Desde</th>
                      <th class="fw-semibold text-nowrap">Hasta</th>
                      <th class="fw-semibold">Categoría</th>
                      <th class="fw-semibold">Día</th>
                      <th class="fw-semibold">Estado</th>
                      <th class="fw-semibold text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($promo = $promociones->fetch_assoc()): ?>
                      <tr>
                        <td class="fw-semibold text-primary">#<?= $promo['codPromo'] ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                              <i class="fas fa-tags text-white"></i>
                            </div>
                            <div>
                              <div class="fw-semibold"><?= htmlspecialchars($promo['textoPromo']) ?></div>
                            </div>
                          </div>
                        </td>
                        <td class="text-muted"><?= htmlspecialchars($promo['nombreLocal']) ?></td>
                        <td class="text-nowrap"><?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?></td>
                        <td class="text-nowrap"><?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></td>
                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($promo['categoriaCliente']) ?></span></td>
                        <td><span class="badge bg-secondary"><?= $diasSemanaMap[$promo['diasSemana']] ?? '-' ?></span></td>
                        <td>
                          <?php if ($promo['estadoPromo'] === 'aprobada'): ?>
                            <span class="badge bg-success">Aprobada</span>
                          <?php elseif ($promo['estadoPromo'] === 'pendiente'): ?>
                            <span class="badge bg-warning text-dark">Pendiente</span>
                          <?php else: ?>
                            <span class="badge bg-danger">Denegada</span>
                          <?php endif; ?>
                        </td>
                        <td class="text-center">
                          <?php if ($promo['estadoPromo'] === 'pendiente'): ?>
                            <form method="POST" action="<?= SRC_URL ?>promociones/validar.php" class="d-inline">
                              <input type="hidden" name="id_promocion" value="<?= $promo['codPromo'] ?>">
                              <button type="submit" name="opcion" value="aprobar" class="btn btn-sm btn-outline-success" title="Aprobar">
                                <i class="fas fa-check"></i>
                              </button>
                              <button type="submit" name="opcion" value="denegar" class="btn btn-sm btn-outline-danger" title="Denegar">
                                <i class="fas fa-times"></i>
                              </button>
                            </form>
                          <?php else: ?>
                            <em>—</em>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
              <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Navegación de páginas" class="mt-4">
                  <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                      <a class="page-link" href="?pagina=<?= $pagina - 1 ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                      <li class="page-item <?= ($pagina == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                      <a class="page-link" href="?pagina=<?= $pagina + 1 ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
                <p class="text-center text-muted small mt-2">
                  Mostrando <?= $promociones->num_rows ?> de <?= $totalRegistros ?> promociones
                </p>
              <?php endif; ?>
            <?php else: ?>
              <div class="text-center py-5">
                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay promociones cargadas</h5>
                <p class="text-muted">Comienza creando tu primera promoción</p>
                <a href="create.php" class="btn btn-warning w-100 w-md-auto">
                  <i class="fas fa-plus me-2"></i>Crear Promoción
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>