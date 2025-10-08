<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$role = $_SESSION['tipoUsuario'] ?? 'guest';

$sections = [
  [
    'title' => 'Descubrir',
    'links' => [
      ['label' => 'Inicio', 'url' => PUBLIC_URL . 'index.php'],
      ['label' => 'Promociones', 'url' => PUBLIC_URL . 'promociones.php'],
      ['label' => 'Locales', 'url' => PUBLIC_URL . 'locales.php'],
      ['label' => 'Contacto', 'url' => PUBLIC_URL . 'index.php#contacto'],
    ],
  ],
  [
    'title' => 'Recursos',
    'links' => [
      ['label' => 'Politica de privacidad', 'url' => '#'],
      ['label' => 'Terminos y condiciones', 'url' => '#'],
      ['label' => 'Soporte', 'url' => PUBLIC_URL . 'index.php#contacto'],
    ],
  ],
];

switch ($role) {
  case 'administrador':
    $sections[] = [
      'title' => 'Gestion administrativa',
      'links' => [
        ['label' => 'Dashboard', 'url' => PUBLIC_URL . 'admin/dashboard.php'],
        ['label' => 'Locales', 'url' => PUBLIC_URL . 'admin/locales/locales.php'],
        ['label' => 'Promociones', 'url' => PUBLIC_URL . 'admin/promociones/promociones.php'],
        ['label' => 'Reportes', 'url' => PUBLIC_URL . 'admin/reportes/uso_promociones.php'],
        ['label' => 'Novedades', 'url' => PUBLIC_URL . 'admin/novedades/novedades.php'],
        ['label' => 'Usuarios', 'url' => PUBLIC_URL . 'admin/usuarios/usuarios.php'],
      ],
    ];
    $sections[] = [
      'title' => 'Tu cuenta',
      'links' => [
        ['label' => 'Cerrar sesion', 'url' => PUBLIC_URL . 'autenticacion/logout.php'],
      ],
    ];
    break;
  case 'dueno de local':
    $sections[] = [
      'title' => 'Gestion del local',
      'links' => [
        ['label' => 'Panel del local', 'url' => PUBLIC_URL . 'locales/index.php'],
        ['label' => 'Nueva promocion', 'url' => PUBLIC_URL . 'locales/create.php'],
        ['label' => 'Promociones del shopping', 'url' => PUBLIC_URL . 'promociones.php'],
      ],
    ];
    $sections[] = [
      'title' => 'Tu cuenta',
      'links' => [
        ['label' => 'Cerrar sesion', 'url' => PUBLIC_URL . 'autenticacion/logout.php'],
      ],
    ];
    break;
  case 'cliente':
    $sections[] = [
      'title' => 'Tu cuenta',
      'links' => [
        ['label' => 'Panel de usuario', 'url' => PUBLIC_URL . 'usuario/panel.php'],
        ['label' => 'Solicitar promociones', 'url' => PUBLIC_URL . 'promociones.php'],
        ['label' => 'Cerrar sesion', 'url' => PUBLIC_URL . 'autenticacion/logout.php'],
      ],
    ];
    break;
  default:
    $sections[] = [
      'title' => 'Tu cuenta',
      'links' => [
        ['label' => 'Iniciar sesion', 'url' => PUBLIC_URL . 'autenticacion/login.php'],
        ['label' => 'Registrarse', 'url' => PUBLIC_URL . 'autenticacion/registro.php'],
      ],
    ];
    break;
}

$currentYear = date('Y');
?>
<footer class="bg-dark text-white pt-5 mt-auto">
  <div class="container">
    <div class="row g-4 align-items-start text-center text-md-start">
      <div class="col-12 col-lg-4">
        <h5 class="fw-semibold mb-3">Rosario Center</h5>
        <p class="text-white-50 mb-4">
          Shopping y comunidad en un mismo lugar. Accede a promociones, locales y servicios desde cualquier dispositivo.
        </p>
      </div>
      <div class="col-12 col-lg-8">
        <div class="row g-4">
          <?php foreach ($sections as $section): ?>
            <div class="col-6 col-md-4">
              <h6 class="text-uppercase text-white-50 small fw-semibold mb-3"><?= htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') ?></h6>
              <ul class="list-unstyled mb-0">
                <?php foreach ($section['links'] as $link): ?>
                  <li class="mb-2">
                    <a
                      class="text-white text-decoration-none"
                      href="<?= htmlspecialchars($link['url'], ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($link['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <hr class="border-secondary my-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <small class="text-white-50">&copy; <?= htmlspecialchars($currentYear, ENT_QUOTES, 'UTF-8') ?> Rosario Center. Todos los derechos reservados.</small>
      <small class="text-white-50">Hecho con dedicacion en Rosario.</small>
    </div>
  </div>
</footer>