<?php
// session_start();

require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../config/config.php';
require_once __DIR__ . '/../../src/locales/model.php';
require_once __DIR__ . '/../../src/novedades/model.php';
require_once __DIR__ . '/../../src/promociones/model.php';
require_once __DIR__ . '/../../src/usuarios/model.php';

// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//   header("Location: ../login.php");
//   exit();
// }

$locales = getAllLocales($conn);
$novedades = getAllNovedades($conn);
$promociones = getAllPromociones($conn);
//acciones???
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
    <div class="sidebar d-none d-md-block" style="width: 250px;">
      <div class="py-4 text-center">
        <h4 class="mb-0">Rosario Center</h4>
        <small>Panel de Administración</small>
      </div>
      <div class="mt-4">
        <a href="<?= BASE_URL ?>public/admin/index.php" class="sidebar-link active">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>public/admin/locales/locales.php" class="sidebar-link">
          <i class="fas fa-store"></i> Locales
        </a>
        <a href="<?= BASE_URL ?>public/admin/promociones/promociones.php" class="sidebar-link">
          <i class="fas fa-tag"></i> Promociones
        </a>
        <a href="<?= BASE_URL ?>public/admin/novedades/novedades.php" class="sidebar-link">
          <i class="fas fa-newspaper"></i> Novedades
        </a>
        <a href="usuarios/" class="sidebar-link">
          <i class="fas fa-users"></i> Usuarios
        </a>
        <hr class="bg-secondary mx-3">
        <a href="../logout.php" class="sidebar-link">
          <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <!-- Header -->
      <header class="admin-header">
        <div class="container-fluid d-flex justify-content-between align-items-center">
          <button class="btn btn-secondary d-md-none">
            <i class="fas fa-bars"></i>
          </button>
          <h1 class="h4 mb-0">Panel de Administración</h1>
          <div class="text-end">
            <span class="me-2">Bienvenido, Admin</span>
            <i class="fas fa-user-circle"></i>
          </div>
        </div>
      </header>

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
                      <h3 class="mb-0">3</h3>
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
              <a href="promociones/create.php" class="card admin-card h-100 text-decoration-none">
                <div class="card-body text-center">
                  <div class="card-icon">
                    <i class="fas fa-percent"></i>
                  </div>
                  <h5 class="card-title">Nueva Promoción</h5>
                  <p class="text-muted">Crear una nueva promoción</p>
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
              <a href="usuarios/" class="card admin-card h-100 text-decoration-none">
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
                    <div class="list-group-item border-0 d-flex align-items-center">
                      <div class="me-3 text-success">
                        <i class="fas fa-check-circle"></i>
                      </div>
                      <div>
                        <small class="text-muted">Hace 2 minutos</small>
                        <p class="mb-1">Promoción "2x1 en cafés" aprobada</p>
                      </div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-center">
                      <div class="me-3 text-primary">
                        <i class="fas fa-store"></i>
                      </div>
                      <div>
                        <small class="text-muted">Hace 30 minutos</small>
                        <p class="mb-1">Nuevo local "Libros y Más" registrado</p>
                      </div>
                    </div>
                    <div class="list-group-item border-0 d-flex align-items-center">
                      <div class="me-3 text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                      </div>
                      <div>
                        <small class="text-muted">Hace 1 hora</small>
                        <p class="mb-1">3 promociones pendientes de aprobación</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Activar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Mobile sidebar toggle
    document.querySelector('.btn-secondary.d-md-none').addEventListener('click', function() {
      document.querySelector('.sidebar').classList.toggle('d-none');
    });
  </script>
</body>

</html>