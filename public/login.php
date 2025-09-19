<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
</head>


<body>

  <?php include_once __DIR__ . '/../includes/header.php'; ?>

  <main class="container min-vh-100 d-flex flex-column justify-content-center">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg p-4">
          <h2 class="card-title text-center mb-4 fw-bold fs-2">Iniciar Sesión</h2>
          <form action="../src/usuarios/login.php" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Correo electrónico</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-5">
              <label for="password" class="form-label fw-semibold">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-warning btn-lg">Iniciar Sesión</button>
            </div>
            <p class="text-center mt-3">¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php include_once __DIR__ . '/../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>