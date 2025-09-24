<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $resultado = loginUsuario($conn, $email, $password);
  if ($resultado["success"]) {
    switch ($_SESSION['tipoUsuario']) {
      case 'administrador':
        header("Location: ../../public/admin/dashboard.php");
        break;
      case 'dueno de local':
        header("Location: ../../public/index.php");
        break;
      case 'cliente':
        header("Location: ../../public/index.php");
        break;
    }
    exit();
  } else {
    echo "Error al iniciar sesión: " . $resultado["error"];
  }
}
