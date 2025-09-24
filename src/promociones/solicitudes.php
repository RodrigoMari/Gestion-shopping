<?php
require_once __DIR__ . '/model.php';
require_once __DIR__ . '/../../src/usuarios/model.php';

if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'dueno de local') {
  die("Acceso denegado.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $codCliente = (int) $_POST['codCliente'];
  $codPromo   = (int) $_POST['codPromo'];
  $accion     = $_POST['accion']; // aceptar o rechazar

  $estado = ($accion === 'aceptar') ? 'aceptada' : 'rechazada';

  $sql = "UPDATE uso_promociones SET estado=? WHERE codCliente=? AND codPromo=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sii", $estado, $codCliente, $codPromo);

  if ($stmt->execute()) {
    if ($estado === 'aceptada') {
      actualizarCategoriaCliente($conn, $codCliente);
    }
    header("Location: ../../public/locales/index.php");
  } else {
    header("Location: ../../public/locales/index.php");
  }
  exit();
}
