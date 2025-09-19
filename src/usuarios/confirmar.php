<?php
require_once __DIR__ . '/model.php';

if (isset($_GET["token"])) {
  $token = $_GET["token"];
  if (validarUsuarioPorToken($conn, $token)) {
    echo "Cuenta validada correctamente. Ya puedes iniciar sesión.";
  } else {
    echo "Token inválido o ya usado.";
  }
}
