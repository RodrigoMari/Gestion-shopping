<section id="contacto" class="contact-section py-5">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6 d-flex flex-column justify-content-center px-5">
        <div class="contact-info text-white">
          <h2 class="display-4 fw-bold mb-4">Contacto</h2>
          <div class="contact-underline mb-5"></div>

          <div class="mb-4">
            <p class="fs-5 mb-1">Junín 501</p>
            <p class="fs-5 mb-4">Rosario, Santa Fe, Argentina</p>
            <p class="fs-5">administracion@rosariocentro.com</p>
          </div>

          <div class="mt-5">
            <h4 class="mb-4">Seguinos en nuestras Redes Sociales</h4>
            <div class="social-icons">
              <a href="#" class="text-white me-3" aria-label="Instagram">
                <i class="fab fa-instagram fa-2x"></i>
              </a>
              <a href="#" class="text-white" aria-label="Facebook">
                <i class="fab fa-facebook fa-2x"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 d-flex align-items-center px-5">
        <div class="contact-form w-100 mt-4 mt-lg-0">
          <?php
          $mensajeFeedback = "";

          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // 1. Sanitización de datos
            $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
            $apellido = htmlspecialchars(trim($_POST['apellido'] ?? ''));
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $telefono = trim($_POST['telefono'] ?? '');
            $mensaje = htmlspecialchars(trim($_POST['mensaje'] ?? ''));

            $errores = [];

            if (empty($nombre) || strlen($nombre) < 2) {
              $errores[] = "El nombre es muy corto.";
            }
            if (empty($apellido) || strlen($apellido) < 2) {
              $errores[] = "El apellido es muy corto.";
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $errores[] = "El formato del correo electrónico no es válido.";
            }
            if (!empty($telefono) && !preg_match('/^[0-9]{8,15}$/', $telefono)) {
              $errores[] = "El teléfono debe contener solo números (mínimo 8, máximo 15 dígitos).";
            }
            if (empty($mensaje)) {
              $errores[] = "El mensaje no puede estar vacío.";
            }

            if (empty($errores)) {
              $destino = "tetix57967@futebr.com"; // Email de prueba
              $asunto = "Nueva Consulta de $nombre $apellido";

              $headers = "MIME-Version: 1.0" . "\r\n";
              $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
              $headers .= "From: no-reply@rosariocenter.com" . "\r\n";
              $headers .= "Reply-To: $email" . "\r\n";

              $cuerpo = "
                <html>
                <head><title>Consulta Web</title></head>
                <body>
                    <h2>Nueva consulta recibida</h2>
                    <p><strong>Nombre:</strong> $nombre $apellido</p>
                    <p><strong>Email:</strong> $email</p>
                    <p><strong>Teléfono:</strong> $telefono</p>
                    <p><strong>Mensaje:</strong></p>
                    <p>$mensaje</p>
                    <p><strong>Newsletter:</strong> " . (isset($_POST['newsletter']) ? 'Sí' : 'No') . "</p>
                </body>
                </html>";

              if (@mail($destino, $asunto, $cuerpo, $headers)) {
                $mensajeFeedback = '<div class="alert alert-success" role="alert">¡Mensaje enviado correctamente! Nos pondremos en contacto pronto.</div>';
              } else {
                $mensajeFeedback = '<div class="alert alert-danger" role="alert">Hubo un error técnico al enviar el correo. Por favor intenta más tarde.</div>';
              }
            } else {
              $listaErrores = implode('<br>', $errores);
              $mensajeFeedback = '<div class="alert alert-warning" role="alert"><strong>Error en el formulario:</strong><br>' . $listaErrores . '</div>';
            }
          }
          ?>

          <?= $mensajeFeedback ?>

          <form method="POST" action="index.php#contacto" novalidate>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="nombre" class="form-label text-white">Nombre *</label>
                <input type="text" class="form-control form-control-lg" id="nombre" name="nombre"
                  placeholder="Tu nombre" required minlength="2"
                  value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>">
              </div>
              <div class="col-md-6">
                <label for="apellido" class="form-label text-white">Apellido *</label>
                <input type="text" class="form-control form-control-lg" id="apellido" name="apellido"
                  placeholder="Tu apellido" required minlength="2"
                  value="<?= isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : '' ?>">
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="email" class="form-label text-white">Email *</label>
                <input type="email" class="form-control form-control-lg" id="email" name="email"
                  placeholder="nombre@ejemplo.com" required
                  value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
              </div>
              <div class="col-md-6">
                <label for="telefono" class="form-label text-white">Teléfono</label>
                <input type="tel" class="form-control form-control-lg" id="telefono" name="telefono"
                  placeholder="Ej: 3415555555"
                  pattern="[0-9]{8,15}"
                  title="Solo números, entre 8 y 15 dígitos"
                  value="<?= isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '' ?>">
                <div class="form-text text-white-50">Solo números, sin guiones ni espacios.</div>
              </div>
            </div>

            <div class="mb-3">
              <label for="mensaje" class="form-label text-white">Mensaje *</label>
              <textarea class="form-control form-control-lg" id="mensaje" name="mensaje"
                rows="4" placeholder="Escribe tu consulta aquí..." required><?= isset($_POST['mensaje']) ? htmlspecialchars($_POST['mensaje']) : '' ?></textarea>
            </div>

            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" value="1"
                <?= isset($_POST['newsletter']) ? 'checked' : '' ?>>
              <label class="form-check-label text-white" for="newsletter">
                Quiero suscribirme al newsletter
              </label>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-warning btn-lg px-5 fw-bold">Enviar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>