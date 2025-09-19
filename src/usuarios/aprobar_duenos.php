<?php
require_once __DIR__ . '/model.php';
session_start();

if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'administrador') {
  die("Acceso denegado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = (int) $_POST['id_usuario'];
  $accion = $_POST['accion'];

  if ($accion === 'aprobar') {
    if (aprobarDuenoLocal($conn, $id)) {
      header("Location: ../../public/admin/duenos.php?msg=ok");
    } else {
      header("Location: ../../public/admin/duenos.php?msg=error");
    }
  } elseif ($accion === 'rechazar') {
    if (rechazarDuenoLocal($conn, $id)) {
      header("Location: ../../public/admin/duenos.php?msg=ok");
    } else {
      header("Location: ../../public/admin/duenos.php?msg=error");
    }
  }
  exit();
}
