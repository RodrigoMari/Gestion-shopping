<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '../../../../src/promociones/model.php';

$success = isset($_GET['success']) ? 'Promoción modificada con éxito.' : null;
$error   = isset($_GET['error']) ? urldecode($_GET['error']) : null;

$promo = null;
if (isset($_GET['id'])) {
  $id_promo = (int)$_GET['id'];
  $promo = getPromoById($conn, $id_promo);

  if ($promo === null) {
    header("Location: index.php?error=" . urlencode("Promoción no encontrada"));
    exit();
  } elseif (is_string($promo)) {
    header("Location: index.php?error=" . urlencode($promo));
    exit();
  }
} else {
  header("Location: index.php?error=" . urlencode("ID de promoción no especificado"));
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Promoción - Rosario Center Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>public/admin/promociones/promociones.php" class="text-decoration-none">Promociones</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar #<?= $promo['codPromo'] ?></li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
              <i class="fas fa-edit me-2"></i>Editar Información de la Promoción
            </h5>
          </div>
          <div class="card-body p-4">

            <?php if ($success): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= $success ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <?php if ($error): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <!-- Información actual -->
            <div class="alert alert-info mb-4">
              <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Promoción Actual:</h6>
              <p class="mb-1"><strong>ID:</strong> <?= $promo['codPromo'] ?></p>
              <p class="mb-1"><strong>Texto:</strong> <?= htmlspecialchars($promo['textoPromo']) ?></p>
              <p class="mb-1"><strong>Fecha Desde:</strong> <?= htmlspecialchars($promo['fechaDesdePromo']) ?></p>
              <p class="mb-1"><strong>Fecha Hasta:</strong> <?= htmlspecialchars($promo['fechaHastaPromo']) ?></p>
              <p class="mb-1"><strong>Categoría Cliente:</strong> <?= htmlspecialchars($promo['categoriaCliente']) ?></p>
              <p class="mb-1"><strong>Días Semana:</strong> <?= htmlspecialchars($promo['diasSemana']) ?></p>
              <p class="mb-1"><strong>Estado:</strong> <?= htmlspecialchars($promo['estadoPromo']) ?></p>
              <p class="mb-0"><strong>Local:</strong> <?= htmlspecialchars($promo['codLocal']) ?></p>
            </div>

            <form method="post" action="<?= SRC_URL ?>promociones/update.php">
              <input type="hidden" name="id_promo" value="<?= $promo['codPromo'] ?>">

              <div class="mb-4">
                <label for="texto_promo" class="form-label fw-semibold"><i class="fas fa-pen me-1"></i>Texto de la Promoción</label>
                <textarea class="form-control form-control-lg" id="texto_promo" name="texto_promo" required maxlength="200" rows="4"><?= isset($_POST['texto_promo']) ? htmlspecialchars($_POST['texto_promo']) : htmlspecialchars($promo['textoPromo']) ?></textarea>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="fecha_desde" class="form-label fw-semibold"><i class="fas fa-calendar-alt me-1"></i>Fecha Desde</label>
                  <input type="date" class="form-control form-control-lg" id="fecha_desde" name="fecha_desde" required value="<?= isset($_POST['fecha_desde']) ? htmlspecialchars($_POST['fecha_desde']) : htmlspecialchars($promo['fechaDesdePromo']) ?>">
                </div>

                <div class="col-md-6 mb-4">
                  <label for="fecha_hasta" class="form-label fw-semibold"><i class="fas fa-calendar-check me-1"></i>Fecha Hasta</label>
                  <input type="date" class="form-control form-control-lg" id="fecha_hasta" name="fecha_hasta" required value="<?= isset($_POST['fecha_hasta']) ? htmlspecialchars($_POST['fecha_hasta']) : htmlspecialchars($promo['fechaHastaPromo']) ?>">
                </div>
              </div>

              <div class="mb-4">
                <label for="categoria_cliente" class="form-label fw-semibold"><i class="fas fa-users me-1"></i>Categoría Cliente</label>
                <select class="form-select form-select-lg" id="categoria_cliente" name="categoria_cliente" required>
                  <?php
                  $categorias = ['Inicial', 'Medium', 'Premium'];
                  $categoriaActual = isset($_POST['categoria_cliente']) ? $_POST['categoria_cliente'] : $promo['categoriaCliente'];
                  foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($categoriaActual == $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-4">
                <label for="dias_semana" class="form-label fw-semibold"><i class="fas fa-calendar-week me-1"></i>Días de la Semana</label>
                <input type="number" min="0" max="6" class="form-control form-control-lg" id="dias_semana" name="dias_semana" required value="<?= isset($_POST['dias_semana']) ? htmlspecialchars($_POST['dias_semana']) : htmlspecialchars($promo['diasSemana']) ?>">
                <div class="form-text">0 = Domingo, 1 = Lunes, ... 6 = Sábado</div>
              </div>

              <div class="mb-4">
                <label for="estado_promo" class="form-label fw-semibold"><i class="fas fa-flag me-1"></i>Estado</label>
                <select class="form-select form-select-lg" id="estado_promo" name="estado_promo" required>
                  <?php
                  $estados = ['aprobada', 'pendiente', 'denegada'];
                  $estadoActual = isset($_POST['estado_promo']) ? $_POST['estado_promo'] : $promo['estadoPromo'];
                  foreach ($estados as $estado): ?>
                    <option value="<?= $estado ?>" <?= ($estadoActual == $estado) ? 'selected' : '' ?>><?= ucfirst($estado) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-4">
                <label for="cod_local" class="form-label fw-semibold"><i class="fas fa-store me-1"></i>Local</label>
                <input type="number" class="form-control form-control-lg" id="cod_local" name="cod_local" required value="<?= isset($_POST['cod_local']) ? htmlspecialchars($_POST['cod_local']) : htmlspecialchars($promo['codLocal']) ?>">
              </div>

              <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                <a href="promociones.php" class="btn btn-outline-secondary btn-lg">
                  <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-warning btn-lg">
                  <i class="fas fa-save me-2"></i>Guardar Cambios
                </button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include '../../../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
