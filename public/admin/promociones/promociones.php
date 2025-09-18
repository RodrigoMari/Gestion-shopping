<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '/../../../src/promociones/model.php';

$promociones = getAllPromociones($conn);
$promosActivasCount = getPromocionesActivasCount($conn);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Promociones - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>
  <?php include '../../../includes/header.php'; ?>

  <main class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>public/admin/index.php" class="text-decoration-none">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Promociones</li>
      </ol>
    </nav>

    <!-- Título y botón de acción -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-dark">Administración de Promociones</h2>
        <p class="text-muted">Gestiona todas las promociones disponibles en el centro comercial</p>
      </div>
      <div class="col-md-4 text-end">
        <a href="create.php" class="btn btn-warning btn-lg">
          <i class="fas fa-plus me-2"></i>Nueva Promoción
        </a>
      </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-5">
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fas fa-tags fa-2x text-info mb-2"></i>
            <h4 class="fw-bold"><?= $promociones->num_rows ?></h4>
            <p class="text-muted mb-0">Promociones Totales</p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
            <h4 class="fw-bold"><?= $promosActivasCount ?></h4>
            <p class="text-muted mb-0">Promociones Activas</p>
          </div>
        </div>
      </div>
      <!-- Puedes agregar otras estadísticas si quieres -->
    </div>

    <!-- Tabla de promociones -->
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
                  <th class="fw-semibold">Desde</th>
                  <th class="fw-semibold">Hasta</th>
                  <th class="fw-semibold">Categoría</th>
                  <th class="fw-semibold">Días Semana</th>
                  <th class="fw-semibold">Estado</th>
                  <th class="fw-semibold text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($promo = $promociones->fetch_assoc()): ?>
                  <tr>
                    <td class="fw-semibold text-primary">#<?= $promo['codPromo'] ?></td>
                    <td><?= htmlspecialchars($promo['textoPromo']) ?></td>
                    <td><?= htmlspecialchars($promo['fechaDesdePromo']) ?></td>
                    <td><?= htmlspecialchars($promo['fechaHastaPromo']) ?></td>
                    <td><?= htmlspecialchars($promo['categoriaCliente']) ?></td>
                    <td><?= htmlspecialchars($promo['diasSemana']) ?></td>
                    <td>
                      <?php if ($promo['estadoPromo'] === 'aprobada'): ?>
                        <span class="badge bg-success text-white"><?= $promo['estadoPromo'] ?></span>
                      <?php else: ?>
                        <span class="badge bg-danger text-white"><?= $promo['estadoPromo'] ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <div class="btn-group" role="group">
                        <a href="edit.php?id=<?= $promo['codPromo'] ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="<?= SRC_URL ?>promociones/delete.php" style="display:inline;">
                          <input type="hidden" name="id_promo" value="<?= $promo['codPromo'] ?>">
                          <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" onclick="return confirm('¿Estás seguro de que quieres eliminar esta promoción?')">
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
            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay promociones registradas</h5>
            <p class="text-muted">Comienza creando tu primera promoción</p>
            <a href="create.php" class="btn btn-warning">
              <i class="fas fa-plus me-2"></i>Crear Primera Promoción
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include '../../../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
