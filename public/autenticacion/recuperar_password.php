<?php
// Página para solicitar el restablecimiento de contraseña
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
  <?php include __DIR__ . '/../../includes/flash_toast.php'; ?>
  <?php include_once __DIR__ . '/../../includes/header.php'; ?>

  <main class="container min-vh-100 d-flex flex-column justify-content-center">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg p-4">
          <h2 class="card-title text-center mb-4 fw-bold fs-4">Recuperar contraseña</h2>
          <p class="text-muted">Ingresa tu correo electrónico y te daremos un enlace para restablecer tu contraseña.</p>
          <form action="../../src/usuarios/solicitar_reset.php" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Correo electrónico <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-warning">Enviar enlace</button>
            </div>
          </form>
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