<?php
require_once '../../../config/database.php';
require_once '../../../config/config.php';
require_once '../../../src/usuarios/model.php';

//$success = isset($_GET['success']) ? 'Local creado con éxito.' : null;
$error = isset($_GET['error']) ? urldecode($_GET['error']) : null;

// Asumimos que $conn (conexión a BBDD) está definida en un archivo incluido antes
// por ejemplo en admin_header.php o similar, ya que getAllUsuariosDuenos la necesita.
$result_duenos = getAllDuenos($conn);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nuevo Local - Rosario Center Admin</title>
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
        <nav aria-label="breadcrumb" class="mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>admin/dashboard.php" class="text-decoration-none">Admin</a></li>
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>admin/locales/locales.php" class="text-decoration-none">Locales</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nuevo</li>
          </ol>
        </nav>

        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-light">
                <h5 class="mb-0 fw-bold">
                  <i class="fas fa-store me-2"></i>Información del Local
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

                <form method="post" action="<?= SRC_URL ?>locales/create.php">
                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <label for="nombre_local" class="form-label fw-semibold">
                        <i class="fas fa-store me-1"></i>Nombre del Local <span class="text-danger">*</span>
                      </label>
                      <input type="text" class="form-control form-control-lg"
                        id="nombre_local" name="nombre_local" required minlength="2" maxlength="100"
                        pattern="^[^<>]{2,100}$" title="Entre 2 y 100 caracteres. No se permiten < ni >"
                        placeholder="Ej: Librería Cervantes"
                        value="<?= isset($_POST['nombre_local']) ? htmlspecialchars($_POST['nombre_local']) : '' ?>">
                      <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Máximo 100 caracteres
                      </div>
                    </div>

                    <div class="col-md-6 mb-4">
                      <label for="ubicacion" class="form-label fw-semibold">
                        <i class="fas fa-map-marker-alt me-1"></i>Ubicación <span class="text-danger">*</span>
                      </label>
                      <input type="text" class="form-control form-control-lg"
                        id="ubicacion" name="ubicacion" required minlength="1" maxlength="50"
                        pattern="^[^<>]{1,50}$" title="Entre 1 y 50 caracteres. No se permiten < ni >"
                        placeholder="Ej: Local 105"
                        value="<?= isset($_POST['ubicacion']) ? htmlspecialchars($_POST['ubicacion']) : '' ?>">
                      <div class="form-text">
                        <i class="fas fa-info-circle me-1"></i>Máximo 50 caracteres
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <label for="rubro" class="form-label fw-semibold">
                        <i class="fas fa-tags me-1"></i>Rubro <span class="text-danger">*</span>
                      </label>
                      <select class="form-select form-select-lg" id="rubro" name="rubro" required>
                        <option value="">Seleccione un rubro</option>
                        <option value="indumentaria" <?= (isset($_POST['rubro']) && $_POST['rubro'] == 'indumentaria') ? 'selected' : '' ?>>Indumentaria</option>
                        <option value="perfumeria" <?= (isset($_POST['rubro']) && $_POST['rubro'] == 'perfumeria') ? 'selected' : '' ?>>Perfumería</option>
                        <option value="optica" <?= (isset($_POST['rubro']) && $_POST['rubro'] == 'optica') ? 'selected' : '' ?>>Óptica</option>
                        <option value="comida" <?= (isset($_POST['rubro']) && $_POST['rubro'] == 'comida') ? 'selected' : '' ?>>Comida</option>
                        <option value="etc" <?= (isset($_POST['rubro']) && $_POST['rubro'] == 'etc') ? 'selected' : '' ?>>Otros</option>
                      </select>
                    </div>

                    <div class="col-md-6 mb-4">
            <label for="id_usuario" class="form-label fw-semibold">
              <i class="fas fa-user me-1"></i>Propietario del Local <span class="text-danger">*</span>
            </label>
                        <select class="form-select form-select-lg" id="id_usuario" name="id_usuario" required>
                            <option value="">Seleccione un propietario</option>
                            <?php
                            if ($result_duenos && mysqli_num_rows($result_duenos) > 0) {
                                while ($dueno = mysqli_fetch_assoc($result_duenos)) {
                                    $id_propietario = htmlspecialchars($dueno['codUsuario']);
                                    $nombre_propietario = htmlspecialchars($dueno['nombreUsuario']);

                                    $selected = (isset($_POST['id_usuario']) && $_POST['id_usuario'] == $dueno['codUsuario']) ? 'selected' : '';

                                    echo "<option value=\"$id_propietario\" $selected>$nombre_propietario (ID: $id_propietario)</option>";
                                }
                            }
                            ?>
                        </select>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Seleccione el usuario que será propietario de este local.
                        </div>
                    </div>
                  </div>

                  <div class="d-flex gap-3 justify-content-end mt-4 pt-3 border-top">
                    <a href="locales.php" class="btn btn-outline-secondary btn-lg">
                      <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning btn-lg">
                      <i class="fas fa-save me-2"></i>Crear Local
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>