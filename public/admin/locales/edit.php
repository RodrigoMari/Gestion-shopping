<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '../../../../src/locales/model.php';

$success = isset($_GET['success']) ? 'Local modificado con éxito.' : null;
$error = isset($_GET['error']) ? urldecode($_GET['error']) : null;

$local = null;
if (isset($_GET['id'])) {
  $id_local = (int)$_GET['id'];
  $local = getLocalById($conn, $id_local);

  if ($local === null) {
    header("Location: index.php?error=" . urlencode("Local no encontrado"));
    exit();
  } elseif (is_string($local)) {
    header("Location: index.php?error=" . urlencode($local));
    exit();
  }
} else {
  header("Location: index.php?error=" . urlencode("ID de local no especificado"));
  exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Local - Rosario Center Admin</title>
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
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>public/admin/locales/locales.php" class="text-decoration-none">Locales</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar #<?= $local['codLocal'] ?></li>
      </ol>
    </nav>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-light">
            <h5 class="mb-0 fw-bold">
              <i class="fas fa-edit me-2"></i>Editar Información del Local
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

            <!-- Información actual del local -->
            <div class="alert alert-info mb-4">
              <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Local Actual:</h6>
              <p class="mb-1"><strong>ID:</strong> <?= $local['codLocal'] ?></p>
              <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars($local['nombreLocal']) ?></p>
              <p class="mb-1"><strong>Ubicación:</strong> <?= htmlspecialchars($local['ubicacionLocal']) ?></p>
              <p class="mb-1"><strong>Rubro:</strong> <?= htmlspecialchars($local['rubroLocal']) ?></p>
              <p class="mb-0"><strong>Usuario:</strong> #<?= $local['codUsuario'] ?></p>
            </div>

            <form method="post" action="<?= SRC_URL ?>locales/update.php">
              <!-- Campo oculto para el ID -->
              <input type="hidden" name="id_local" value="<?= $local['codLocal'] ?>">

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="nombre_local" class="form-label fw-semibold">
                    <i class="fas fa-store me-1"></i>Nombre del Local
                  </label>
                  <input type="text" class="form-control form-control-lg"
                    id="nombre_local" name="nombre_local" required
                    placeholder="Ej: Librería Cervantes"
                    maxlength="100"
                    value="<?= isset($_POST['nombre_local']) ? htmlspecialchars($_POST['nombre_local']) : htmlspecialchars($local['nombreLocal']) ?>">
                  <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>Máximo 100 caracteres
                  </div>
                </div>

                <div class="col-md-6 mb-4">
                  <label for="ubicacion" class="form-label fw-semibold">
                    <i class="fas fa-map-marker-alt me-1"></i>Ubicación
                  </label>
                  <input type="text" class="form-control form-control-lg"
                    id="ubicacion" name="ubicacion" required
                    placeholder="Ej: Local 105"
                    maxlength="50"
                    value="<?= isset($_POST['ubicacion']) ? htmlspecialchars($_POST['ubicacion']) : htmlspecialchars($local['ubicacionLocal']) ?>">
                  <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>Máximo 50 caracteres
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="rubro" class="form-label fw-semibold">
                    <i class="fas fa-tags me-1"></i>Rubro
                  </label>
                  <select class="form-select form-select-lg" id="rubro" name="rubro" required>
                    <option value="">Seleccione un rubro</option>
                    <?php
                    $rubros = ['indumentaria', 'perfumería', 'óptica', 'comida', 'etc'];
                    $rubroActual = isset($_POST['rubro']) ? $_POST['rubro'] : $local['rubroLocal'];
                    foreach ($rubros as $rubro):
                    ?>
                      <option value="<?= $rubro ?>" <?= ($rubroActual == $rubro) ? 'selected' : '' ?>>
                        <?= ucfirst($rubro == 'etc' ? 'Otros' : $rubro) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-6 mb-4">
                  <label for="id_usuario" class="form-label fw-semibold">
                    <i class="fas fa-user me-1"></i>ID del Usuario Propietario
                  </label>
                  <input type="number" class="form-control form-control-lg"
                    id="id_usuario" name="id_usuario" required
                    placeholder="Ej: 123"
                    value="<?= isset($_POST['id_usuario']) ? htmlspecialchars($_POST['id_usuario']) : $local['codUsuario'] ?>">
                  <div class="form-text">
                    <i class="fas fa-info-circle me-1"></i>Ingrese el ID del usuario propietario de este local
                  </div>
                </div>
              </div>

              <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                <a href="index.php" class="btn btn-outline-secondary btn-lg">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>