<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '/../../../src/promociones/model.php';

$promociones = getAllPromociones($conn);
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/style.css">
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

    <!-- Título -->
    <div class="row mb-4">
      <div class="col-md-8">
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
            <p class="text-muted mb-0">Promociones Aprobadas</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla de promociones -->
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Lista de Promociones</h5>
      </div>
      <div class="card-body p-0">
        <?php if (is_object($promociones) && $promociones->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-hover table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Promoción</th>
                  <th>Local</th>
                  <th class="text-nowrap">Desde</th>
                  <th class="text-nowrap">Hasta</th>
                  <th>Categoría</th>
                  <th>Día</th>
                  <th>Estado</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($promo = $promociones->fetch_assoc()): ?>
                  <tr>
                    <td>#<?= $promo['codPromo'] ?></td>
                    <td><?= htmlspecialchars($promo['textoPromo']) ?></td>
                    <td><?= htmlspecialchars($promo['nombreLocal']) ?></td>
                    <td class="text-nowrap"><?= date('d/m/Y', strtotime($promo['fechaDesdePromo'])) ?></td>
                    <td class="text-nowrap"><?= date('d/m/Y', strtotime($promo['fechaHastaPromo'])) ?></td>
                    <td><?= $promo['categoriaCliente'] ?></td>
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
                          <button type="submit" name="opcion" value="aprobar" class="btn btn-sm btn-success">Aprobar</button>
                          <button type="submit" name="opcion" value="denegar" class="btn btn-sm btn-danger">Denegar</button>
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
        <?php else: ?>
          <div class="alert alert-info m-3">No hay promociones cargadas.</div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include '../../../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>