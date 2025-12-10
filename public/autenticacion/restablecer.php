<?php
// Página para establecer nueva contraseña usando token
include_once __DIR__ . '/../../config/database.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer contraseña - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
  <?php include __DIR__ . '/../../includes/flash_toast.php'; ?>
  <?php include_once __DIR__ . '/../../includes/header.php'; ?>

  <main class="container min-vh-100 d-flex flex-column justify-content-center">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg p-4">
          <h2 class="card-title text-center mb-4 fw-bold fs-4">Restablecer contraseña</h2>
          <?php if (!$token): ?>
            <div class="alert alert-danger">Token inválido. Solicita nuevamente el enlace.</div>
          <?php else: ?>
            <form action="../../src/usuarios/realizar_reset.php" method="POST">
              <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Nueva contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" required>
              </div>
              <div class="mb-3">
                <label for="password2" class="form-label fw-semibold">Confirmar contraseña <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password2" name="password2" minlength="8" required>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-warning">Guardar nueva contraseña</button>
              </div>
            </form>
          <?php endif; ?>
          <div class="mt-3 text-center">
            <a href="login.php">Volver al inicio de sesión</a>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
