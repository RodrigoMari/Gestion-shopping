<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../helpers/flash.php';

if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'administrador') {
  die("Acceso denegado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = (int) $_POST['id_usuario'];
  $accion = $_POST['accion'];

  if ($accion === 'aprobar') {
    aprobarDuenoLocal($conn, $id);
    setFlashMessage('success', 'Dueño aprobado exitosamente');
    header("Location: ../../public/admin/usuarios/usuarios.php");
  } elseif ($accion === 'rechazar') {
    rechazarDuenoLocal($conn, $id);
    setFlashMessage('success', 'Dueño rechazado exitosamente');
    header("Location: ../../public/admin/usuarios/usuarios.php");
  exit();
  }
}