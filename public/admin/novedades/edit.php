<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '../../../../src/novedades/model.php';


$novedad = null;
if (isset($_GET['id'])) {
  $id_novedad = (int)$_GET['id'];
  $novedad = getNovedadById($conn, $id_novedad);
  if ($novedad === null) {
    header("Location: edit.php?id=" . urlencode("Novedad no encontrada"));
    exit();
  }
} else {
  header("Location: edit.php?id=" . urlencode("ID de novedad no especificado"));
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Novedad - Rosario Center Admin</title>
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
      <main class="container my-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>admin/dashboard.php" class="text-decoration-none">Admin</a></li>
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>admin/novedades/novedades.php" class="text-decoration-none">Novedades</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar #<?= $novedad['codNovedad'] ?></li>
          </ol>
        </nav>

        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">
                  <i class="fas fa-edit me-2"></i>Editar Información de la Novedad
                </h5>
              </div>
              <div class="card-body p-4">

                <?php if (isset($success)): ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                <?php endif; ?>

                <!-- Información actual de la novedad -->
                <div class="alert alert-info mb-4">
                  <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Novedad Actual:</h6>
                  <p class="mb-1"><strong>ID:</strong> <?= $novedad['codNovedad'] ?></p>
                  <p class="mb-1"><strong>Texto:</strong> <?= htmlspecialchars($novedad['textoNovedad']) ?></p>
                  <p class="mb-1"><strong>Fecha Desde:</strong> <?= htmlspecialchars($novedad['fechaDesdeNovedad']) ?></p>
                  <p class="mb-1"><strong>Fecha Hasta:</strong> <?= htmlspecialchars($novedad['fechaHastaNovedad']) ?></p>
                  <p class="mb-0"><strong>Tipo Usuario:</strong> <?= htmlspecialchars($novedad['tipoUsuario']) ?></p>
                </div>

                <form method="post" action="<?= SRC_URL ?>novedades/update.php">
                  <!-- Campo oculto para el ID -->
                  <input type="hidden" name="id_novedad" value="<?= $novedad['codNovedad'] ?>">

                  <div class="mb-4">
                    <label for="texto_novedad" class="form-label fw-semibold">
                      <i class="fas fa-pen me-1"></i>Texto de la Novedad <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control form-control-lg"
                      id="texto_novedad" name="texto_novedad" required
                      placeholder="Ingrese el texto de la novedad..."
                      maxlength="200" minlength="5" pattern="^[^<>]{5,200}$" title="Entre 5 y 200 caracteres. No se permiten < ni >" rows="4"><?= isset($_POST['texto_novedad']) ? htmlspecialchars($_POST['texto_novedad']) : htmlspecialchars($novedad['textoNovedad']) ?></textarea>
                    <div class="form-text">
                      <i class="fas fa-info-circle me-1"></i>Máximo 200 caracteres
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <label for="fecha_desde" class="form-label fw-semibold">
                        <i class="fas fa-calendar-alt me-1"></i>Fecha Desde <span class="text-danger">*</span>
                      </label>
                      <input type="date" class="form-control form-control-lg"
                        id="fecha_desde" name="fecha_desde" required
                        value="<?= isset($_POST['fecha_desde']) ? htmlspecialchars($_POST['fecha_desde']) : htmlspecialchars($novedad['fechaDesdeNovedad']) ?>">
                      <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Fecha de inicio de la novedad
                      </div>
                    </div>

                    <div class="col-md-6 mb-4">
                      <label for="fecha_hasta" class="form-label fw-semibold">
                        <i class="fas fa-calendar-check me-1"></i>Fecha Hasta <span class="text-danger">*</span>
                      </label>
                      <input type="date" class="form-control form-control-lg"
                        id="fecha_hasta" name="fecha_hasta" required
                        value="<?= isset($_POST['fecha_hasta']) ? htmlspecialchars($_POST['fecha_hasta']) : htmlspecialchars($novedad['fechaHastaNovedad']) ?>">
                      <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Fecha de finalización de la novedad
                      </div>
                    </div>
                  </div>

                  <div class="mb-4">
                    <label for="tipo_usuario" class="form-label fw-semibold">
                      <i class="fas fa-users me-1"></i>Tipo de Usuario <span class="text-danger">*</span>
                    </label>
                    <select class="form-select form-select-lg" id="tipo_usuario" name="tipo_usuario" required>
                      <option value="">Seleccione el tipo de usuario</option>
                      <?php
                      $tipos = ['administrador', 'dueño de local', 'cliente'];
                      $tipoActual = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : $novedad['tipoUsuario'];
                      foreach ($tipos as $tipo):
                      ?>
                        <option value="<?= $tipo ?>" <?= ($tipoActual == $tipo) ? 'selected' : '' ?>>
                          <?= ucfirst($tipo) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <div class="form-text">
                      <i class="fas fa-info-circle me-1"></i>Seleccione qué tipo de usuarios podrán ver esta novedad
                    </div>
                  </div>

                  <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                    <a href="novedades.php" class="btn btn-outline-secondary btn-lg">
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
    </div>
  </div>

  <?php include '../../../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    // Validación de fechas
    const fechaDesde = document.getElementById('fecha_desde');
    const fechaHasta = document.getElementById('fecha_hasta');

    function actualizarMinFechaHasta() {
      if (fechaDesde.value) {
        fechaHasta.min = fechaDesde.value;
        if (fechaHasta.value && fechaHasta.value < fechaDesde.value) {
          fechaHasta.value = fechaDesde.value;
        }
      }
    }

    fechaDesde.addEventListener('change', actualizarMinFechaHasta);
    fechaHasta.addEventListener('change', function() {
      if (this.value < fechaDesde.value) {
        this.value = fechaDesde.value;
        alert('La fecha hasta debe ser mayor o igual a la fecha desde.');
      }
    });

    actualizarMinFechaHasta();
  </script>
</body>

</html>