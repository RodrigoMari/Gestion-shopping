<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $resultado = loginUsuario($conn, $email, $password);

  if ($resultado["success"]) {
    switch ($_SESSION['tipoUsuario']) {
      case 'administrador':
        header("Location: ../../public/admin/index.php");
        break;
      case 'dueño de local':
        header("Location: ../../public/locales.php");
        break;
      case 'cliente':
        header("Location: ../../public/promociones.php");
        break;
    }
    exit();
  } else {
    echo "Error al iniciar sesión: " . $resultado["error"];
  }
}
