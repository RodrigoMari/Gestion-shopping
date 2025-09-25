<?php
require_once __DIR__ . "/../config/config.php";
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<header class="custom-header position-fixed top-0 start-0 w-100" style="z-index: 1030; backdrop-filter: blur(10px); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
  <a class="navbar-brand fw-semibold" href="<?= PUBLIC_URL ?>index.php">
        <img src="<?= IMG_URL ?>shopping-bag2.PNG" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
        Rosario Center
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link fw-semibold <?= ($currentPage == 'index.php') ? 'active' : '' ?>" aria-current="page" href="<?= PUBLIC_URL ?>index.php">Inicio</a>
          </li>
          <?php if (isset($_SESSION['tipoUsuario']) && $_SESSION['tipoUsuario'] === 'administrador'): ?>
            <li class="nav-item">
              <a class="nav-link fw-semibold" href="<?= PUBLIC_URL ?>admin/dashboard.php">Dashboard</a>
            </li>
          <?php endif; ?>
          <li class="nav-item">
            <a class="nav-link fw-semibold <?= ($currentPage == 'promociones.php') ? 'active' : '' ?>" href="<?= PUBLIC_URL ?>promociones.php">Promociones</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="#">Locales</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="#">Contacto</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle fw-semibold" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php if (isset($_SESSION['codUsuario'])): ?>
                <?= ucfirst($_SESSION['tipoUsuario']) ?>
              <?php else: ?>
                Mi Cuenta
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php if (!isset($_SESSION['codUsuario'])): ?>
                <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>autenticacion/login.php">Iniciar Sesión</a></li>
                <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>autenticacion/registro.php">Registrarse</a></li>
              <?php else: ?>
                <?php if ($_SESSION['tipoUsuario'] === 'administrador'): ?>
                  <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>admin/dashboard.php">Panel Admin</a></li>
                <?php elseif ($_SESSION['tipoUsuario'] === 'dueno de local'): ?>
                  <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>locales/index.php">Panel Local</a></li>
                <?php else: ?>
                  <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>usuario/panel.php">Panel Usuario</a></li>
                <?php endif; ?>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="<?= PUBLIC_URL ?>autenticacion/logout.php">Cerrar Sesión</a></li>
              <?php endif; ?>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>