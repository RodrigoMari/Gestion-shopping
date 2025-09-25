<?php
require_once __DIR__ . '/../config/config.php';

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div id="app-fixed-sidebar" class="sidebar d-none d-md-block position-fixed top-0 start-0 vh-100" style="width: 250px; overflow-y:auto; z-index:1050;">
    <div class="py-4 text-center">
        <h4 class="mb-0">Rosario Center</h4>
        <small>Panel de Administración</small>
    </div>
    <div class="mt-4">
        <a href="<?= PUBLIC_URL ?>admin/dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?= PUBLIC_URL ?>admin/locales/locales.php" class="sidebar-link <?= $currentPage === 'locales.php' ? 'active' : '' ?>">
            <i class="fas fa-store"></i> Locales
        </a>
        <a href="<?= PUBLIC_URL ?>admin/promociones/promociones.php" class="sidebar-link <?= $currentPage === 'promociones.php' ? 'active' : '' ?>">
            <i class="fas fa-tag"></i> Promociones
        </a>
        <a href="<?= PUBLIC_URL ?>admin/novedades/novedades.php" class="sidebar-link <?= $currentPage === 'novedades.php' ? 'active' : '' ?>">
            <i class="fas fa-newspaper"></i> Novedades
        </a>
        <a href="<?= PUBLIC_URL ?>admin/usuarios/usuarios.php" class="sidebar-link <?= $currentPage === 'usuarios.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Usuarios
        </a>
        <hr class="bg-secondary mx-3">
        <a href="<?= PUBLIC_URL ?>index.php" class="sidebar-link">
            <i class="fas fa-sign-out-alt"></i> Página principal
        </a>
        <a href="<?= PUBLIC_URL ?>admin/admin_logout.php" class="sidebar-link">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
    </div>
</div>