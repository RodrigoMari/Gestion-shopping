<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '/../../../src/novedades/model.php';

$novedades = getAllNovedades($conn);
$novedadesVigentes = obtenerNovedadesVigentes($conn);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Novedades - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>
  <div class="d-flex">
    <?php include '../../../includes/flash_toast.php'; ?>
    <?php include '../../../includes/sidebar.php'; ?>
    <div class="flex-grow-1">
      <?php include '../../../includes/admin_header.php'; ?>
      <main class="container my-5 px-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>admin/dashboard.php" class="text-decoration-none">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Novedades</li>
          </ol>
        </nav>

        <!-- Título y botón de acción -->
        <div class="row mb-4">
          <div class="col-md-8">
            <h2 class="fw-bold text-dark">Administración de Novedades</h2>
            <p class="text-muted">Gestiona todos las novedades del centro comercial</p>
          </div>
          <div class="col-md-4 text-end">
            <a href="create.php" class="btn btn-warning btn-lg">
              <i class="fas fa-plus me-2"></i>Nueva Novedad
            </a>
          </div>
        </div>

        <!-- Estadísticas rápidas -->
        <div class="row mb-5">
          <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
              <div class="card-body">
                <i class="fas fa-calendar-check fa-2x text-warning mb-2"></i>
                <h4 class="fw-bold">
                  <?php
                  if (is_object($novedades)) {
                    echo $novedades->num_rows;
                    $novedades->data_seek(0); // Reset pointer
                  } else {
                    echo '0';
                  }
                  ?>
                </h4>
                <p class="text-muted mb-0">Novedades Totales</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
              <div class="card-body">
                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                <h4 class="fw-bold"><?= $novedadesVigentes->num_rows ?></h4>
                <p class="text-muted mb-0">Novedades Activas</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
              <div class="card-body">
                <i class="fas fa-tags fa-2x text-info mb-2"></i>
                <h4 class="fw-bold">--</h4>
                <p class="text-muted mb-0">Rubros</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center shadow-sm border-0">
              <div class="card-body">
                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                <h4 class="fw-bold">--</h4>
                <p class="text-muted mb-0">Propietarios</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabla de novedades -->
        <div class="card shadow-sm border-0">
          <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">Lista de Novedades</h5>
          </div>
          <div class="card-body p-0">
            <?php if (is_object($novedades) && $novedades->num_rows > 0): ?>
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="fw-semibold">ID</th>
                      <th class="fw-semibold">Texto</th>
                      <th class="fw-semibold">Fecha Desde</th>
                      <th class="fw-semibold">Fecha Hasta</th>
                      <th class="fw-semibold">Usuario</th>
                      <th class="fw-semibold text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($novedad = $novedades->fetch_assoc()): ?>
                      <tr>
                        <td class="fw-semibold text-primary">#<?= $novedad['codNovedad'] ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                              <i class="fas fa-calendar-check text-white"></i>
                            </div>
                            <div>
                              <div class="fw-semibold"><?= htmlspecialchars($novedad['textoNovedad']) ?></div>
                            </div>
                          </div>
                        </td>
                        <td class="text-muted"><?= htmlspecialchars($novedad['fechaDesdeNovedad']) ?></td>
                        <td class="text-muted"><?= htmlspecialchars($novedad['fechaHastaNovedad']) ?></td>
                        <td class="text-muted"><?= $novedad['tipoUsuario'] ?></td>
                        <td class="text-center">
                          <div class="btn-group" role="group">
                            <a href="edit.php?id=<?= $novedad['codNovedad'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                              <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="<?= SRC_URL ?>novedades/delete.php" style="display:inline;">
                              <input type="hidden" name="id_novedad" value="<?= $novedad['codNovedad'] ?>">
                              <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar esta novedad?')">
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="text-center py-5">
                <i class="fas fa-store fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay novedades registrados</h5>
                <p class="text-muted">Comienza creando tu primera novedad</p>
                <a href="create.php" class="btn btn-warning">
                  <i class="fas fa-plus me-2"></i>Crear Primer Novedad
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