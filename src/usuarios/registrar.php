<?php
require_once __DIR__ . '/model.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];
  $tipoUsuario = $_POST["tipoUsuario"];

  $resultado = registrarUsuario($conn, $email, $password, $tipoUsuario);

  if ($resultado["success"]) {
    $token = $resultado["token"];
    $link = "http://localhost/gestion-shopping/src/usuarios/confirmar.php?token=" . $token;

    $asunto = "Confirmación de cuenta - Shopping Rosario";
    $mensaje = "Hola, gracias por registrarte.\n\nValida tu cuenta aquí:\n$link";
    $headers = "From: no-reply@shopping.com\r\n";

    if ($tipoUsuario == "cliente") {
      if (mail($email, $asunto, $mensaje, $headers)) {
        echo "Registro exitoso. Revisa tu correo para validar la cuenta.";
      } else {
        echo "Usuario creado, pero no se pudo enviar el correo.";
      }
    } else {
      echo "Registro exitoso. Un administrador debe aprobar tu cuenta.";
    }
  } else {
    echo "Error: " . $resultado["error"];
  }
}
