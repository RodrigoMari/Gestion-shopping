<?php

require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../config/config.php';
require_once __DIR__ . '/../../src/locales/model.php';
require_once __DIR__ . '/../../src/novedades/model.php';
require_once __DIR__ . '/../../src/promociones/model.php';
require_once __DIR__ . '/../../src/usuarios/model.php';

$locales = getAllLocales($conn);
$novedades = getAllNovedades($conn);
$promociones = getAllPromociones($conn);

$duenosPendientes = getDuenosPendientes($conn);
$promosPendientes = getPromocionesPendientes($conn);
$ultimosLocales = $conn->query("SELECT * FROM locales ORDER BY codLocal DESC LIMIT 2");
$ultimasPromos = $conn->query("SELECT p.textoPromo, l.nombreLocal, p.fechaDesdePromo
                              FROM promociones p
                              JOIN locales l ON p.codLocal = l.codLocal
                              ORDER BY p.codPromo DESC LIMIT 2");
$ultimasNovedades = $conn->query("SELECT * FROM novedades ORDER BY codNovedad DESC LIMIT 1");
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <?php include '../../includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <!-- Header -->
      <?php include '../../includes/admin_header.php'; ?>
      <!-- Main Content -->
      <main class="admin-main">
        <div class="container-fluid">
          <div class="row mb-4">
            <div class="col-12">
              <h2 class="h4">Resumen General</h2>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="row mb-4">
            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card admin-card stats-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Locales Activos</h6>
                      <h3 class="mb-0"><?= $locales->num_rows ?></h3>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-store"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card admin-card stats-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Promociones Activas</h6>
                      <h3 class="mb-0"><?= $promociones->num_rows ?></h3>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-tag"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card admin-card stats-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Novedades</h6>
                      <h3 class="mb-0"><?= $novedades->num_rows ?></h3>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-newspaper"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
              <div class="card admin-card stats-card warning h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Acciones Pendientes</h6>
                      <h3 class="mb-0">
                        <?= $duenosPendientes->num_rows + $promosPendientes->num_rows ?>
                      </h3>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-exclamation-circle"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Access -->
          <div class="row mb-4">
            <div class="col-12">
              <h2 class="h4">Acciones Rápidas</h2>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
              <a href="locales/create.php" class="card admin-card h-100 text-decoration-none">
                <div class="card-body text-center">
                  <div class="card-icon">
                    <i class="fas fa-plus-circle"></i>
                  </div>
                  <h5 class="card-title">Nuevo Local</h5>
                  <p class="text-muted">Agregar un nuevo local al shopping</p>
                </div>
              </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
              <a href="novedades/create.php" class="card admin-card h-100 text-decoration-none">
                <div class="card-body text-center">
                  <div class="card-icon">
                    <i class="fas fa-bullhorn"></i>
                  </div>
                  <h5 class="card-title">Nueva Novedad</h5>
                  <p class="text-muted">Publicar una novedad</p>
                </div>
              </a>
            </div>

            <div class="col-md-6 col-lg-3 mb-4">
              <a href="usuarios/usuarios.php" class="card admin-card h-100 text-decoration-none">
                <div class="card-body text-center">
                  <div class="card-icon">
                    <i class="fas fa-user-plus"></i>
                  </div>
                  <h5 class="card-title">Administrar Usuarios</h5>
                  <p class="text-muted">Gestionar usuarios del sistema</p>
                </div>
              </a>
            </div>
          </div>

          <!-- Recent Activity -->
          <div class="row mt-5">
            <div class="col-12">
              <div class="card admin-card">
                <div class="card-body">
                  <h2 class="h4 mb-4">Actividad Reciente</h2>
                  <div class="list-group">
                    <?php while ($p = $ultimasPromos->fetch_assoc()): ?>
                      <div class="list-group-item border-0 d-flex align-items-center">
                        <div class="me-3 text-success"><i class="fas fa-check-circle"></i></div>
                        <div>
                          <small class="text-muted">Promoción creada</small>
                          <p class="mb-1"><?= htmlspecialchars($p['textoPromo']) ?> (<?= $p['nombreLocal'] ?>)</p>
                        </div>
                      </div>
                    <?php endwhile; ?>

                    <?php while ($l = $ultimosLocales->fetch_assoc()): ?>
                      <div class="list-group-item border-0 d-flex align-items-center">
                        <div class="me-3 text-primary"><i class="fas fa-store"></i></div>
                        <div>
                          <small class="text-muted">Nuevo local</small>
                          <p class="mb-1"><?= htmlspecialchars($l['nombreLocal']) ?></p>
                        </div>
                      </div>
                    <?php endwhile; ?>

                    <?php while ($n = $ultimasNovedades->fetch_assoc()): ?>
                      <div class="list-group-item border-0 d-flex align-items-center">
                        <div class="me-3 text-warning"><i class="fas fa-bullhorn"></i></div>
                        <div>
                          <small class="text-muted">Nueva novedad</small>
                          <p class="mb-1"><?= htmlspecialchars($n['textoNovedad']) ?></p>
                        </div>
                      </div>
                    <?php endwhile; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
          var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
          var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
          });

          document.querySelector('.btn-secondary.d-md-none').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('d-none');
          });
        </script>
</body>

</html>