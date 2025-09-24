<?php
require_once __DIR__ . '/model.php';


if (!isset($_SESSION['codUsuario'])) {
  die("Debes iniciar sesión para solicitar una promoción.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['codPromo'])) {
  $codCliente = $_SESSION['codUsuario'];
  $codPromo = (int) $_POST['codPromo'];

  $resultado = solicitarPromocion($conn, $codCliente, $codPromo);

  if ($resultado['success']) {
    echo "Solicitud enviada correctamente. El local debe aprobarla.";
  } else {
    echo "Error: " . $resultado['error'];
  }
}
