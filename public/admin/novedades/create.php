<?php
//$success = isset($_GET['success']) ? 'Novedad creada con éxito.' : null;
$error = isset($_GET['error']) ? urldecode($_GET['error']) : null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nueva Novedad - Rosario Center Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>public/admin/novedades/novedades.php" class="text-decoration-none">Novedades</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nueva</li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
              <i class="fas fa-newspaper me-2"></i>Información de la Novedad
            </h5>
          </div>
          <div class="card-body p-4">

            <?php if (isset($error)): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            <?php endif; ?>

            <form method="post" action="<?= SRC_URL ?>novedades/create.php">
              <div class="mb-4">
                <label for="texto_novedad" class="form-label fw-semibold">
                  <i class="fas fa-pen me-1"></i>Texto de la Novedad
                </label>
                <textarea class="form-control form-control-lg"
                  id="texto_novedad" name="texto_novedad" required
                  placeholder="Ingrese el texto de la novedad..."
                  maxlength="200" rows="4"><?= isset($_POST['texto_novedad']) ? htmlspecialchars($_POST['texto_novedad']) : '' ?></textarea>
                <div class="form-text d-flex justify-content-between">
                  <span><i class="fas fa-info-circle me-1"></i>Máximo 200 caracteres</span>
                  <span id="charCount">0/200</span>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="fecha_desde" class="form-label fw-semibold">
                    <i class="fas fa-calendar-alt me-1"></i>Fecha Desde
                  </label>
                  <input type="date" class="form-control form-control-lg"
                    id="fecha_desde" name="fecha_desde" required
                    min="<?= date('Y-m-d') ?>"
                    value="<?= isset($_POST['fecha_desde']) ? htmlspecialchars($_POST['fecha_desde']) : date('Y-m-d') ?>">
                  <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>Fecha de inicio de la novedad
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <label for="fecha_hasta" class="form-label fw-semibold">
                    <i class="fas fa-calendar-check me-1"></i>Fecha Hasta
                  </label>
                  <input type="date" class="form-control form-control-lg"
                    id="fecha_hasta" name="fecha_hasta" required
                    min="<?= date('Y-m-d') ?>"
                    value="<?= isset($_POST['fecha_hasta']) ? htmlspecialchars($_POST['fecha_hasta']) : '' ?>">
                  <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>Fecha de finalización de la novedad
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <label for="tipo_usuario" class="form-label fw-semibold">
                  <i class="fas fa-users me-1"></i>Tipo de Usuario
                </label>
                <select class="form-select form-select-lg" id="tipo_usuario" name="tipo_usuario" required>
                  <option value="">Seleccione el tipo de usuario</option>
                  <option value="administrador" <?= (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == 'administrador') ? 'selected' : '' ?>>
                    <i class="fas fa-user-shield"></i> Administrador
                  </option>
                  <option value="dueño de local" <?= (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == 'dueño de local') ? 'selected' : '' ?>>
                    <i class="fas fa-store"></i> Dueño de Local
                  </option>
                  <option value="cliente" <?= (isset($_POST['tipo_usuario']) && $_POST['tipo_usuario'] == 'cliente') ? 'selected' : '' ?>>
                    <i class="fas fa-user"></i> Cliente
                  </option>
                </select>
                <div class="form-text">
                  <i class="fas fa-info-circle me-1"></i>Seleccione qué tipo de usuarios podrán ver esta novedad
                </div>
              </div>

              <!-- Vista previa -->
              <div class="card bg-light mb-4" id="preview" style="display: none;">
                <div class="card-header">
                  <h6 class="mb-0 fw-bold">
                    <i class="fas fa-eye me-1"></i>Vista Previa
                  </h6>
                </div>
                <div class="card-body">
                  <div class="d-flex align-items-start">
                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                      <i class="fas fa-newspaper text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p id="preview-text" class="mb-2"></p>
                      <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                          <i class="fas fa-calendar me-1"></i>
                          <span id="preview-dates"></span>
                        </small>
                        <small>
                          <span id="preview-badge" class="badge"></span>
                        </small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                <a href="novedades.php" class="btn btn-outline-secondary btn-lg">
                  <i class="fas fa-times me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-warning btn-lg">
                  <i class="fas fa-save me-2"></i>Crear Novedad
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include '../../../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>