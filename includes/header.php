<?php require_once __DIR__ . "/../config/config.php"; ?>
<header class="custom-header">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand fw-semibold" href="<?= BASE_URL ?>public/index.php">
        <img src="<?= IMG_URL ?>shopping-bag2.PNG" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
        Rosario Center
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active fw-semibold" aria-current="page" href="<?= BASE_URL ?>public/index.php">Inicio</a>
          </li>
          <!-- DESPUES OCULTAR PARA NO ADMINS -->
          <li class="nav-item">
            <a class="nav-link fw-semibold" aria-current="page" href="<?= BASE_URL ?>public/admin/index.php">ADMIN</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="#promociones">Promociones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="#">Locales</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="#">Contacto</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Mi Cuenta
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="login.php">Iniciar Sesión</a></li>
              <li><a class="dropdown-item" href="registro.php">Registrarse</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#">Panel de Usuario</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>