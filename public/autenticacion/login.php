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
  <link rel="stylesheet" href="../../css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .password-toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #6c757d;
    }

    .password-toggle:hover {
      color: #495057;
    }

    .position-relative {
      position: relative;
    }
  </style>
</head>

<body>
  <?php include __DIR__ . '/../../includes/flash_toast.php'; ?>

  <?php include_once __DIR__ . '../../../includes/header.php'; ?>

  <main class="container min-vh-100 d-flex flex-column justify-content-center">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-lg p-4">
          <h2 class="card-title text-center mb-4 fw-bold fs-2">Iniciar Sesion</h2>
          <form action="../../src/usuarios/login.php" method="POST">
            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Correo electronico <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label fw-semibold">Contrasena <span class="text-danger">*</span></label>
              <div class="position-relative">
                <input type="password" class="form-control" id="password" name="password" required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
              </div>
            </div>
            <div class="mb-3 text-end">
              <a href="recuperar_password.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-warning btn-lg">Iniciar Sesion</button>
            </div>
            <p class="text-center mt-3">No tienes una cuenta? <a href="registro.php">Registrate aqui</a></p>
          </form>
        </div>
      </div>
    </div>
  </main>

  <?php include_once __DIR__ . '../../../includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script>
    // Toggle password visibility
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function() {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>

</html>