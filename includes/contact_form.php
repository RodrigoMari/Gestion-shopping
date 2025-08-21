<!-- Sección de Contacto -->
<section class="contact-section py-5">
  <div class="container-fluid">
    <div class="row">
      <!-- Columna Izquierda - Información -->
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
              <a href="#" class="text-white me-3">
                <i class="fab fa-instagram fa-2x"></i>
              </a>
              <a href="#" class="text-white">
                <i class="fab fa-facebook fa-2x"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Columna Derecha - Formulario -->
      <div class="col-lg-6 d-flex align-items-center px-5">
        <div class="contact-form w-100">
          <?php
          if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email = $_POST['email'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $mensaje = $_POST['mensaje'] ?? '';
            $destino = "tetix57967@futebr.com";
            $asunto = "Consulta";

            if (isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['email']) && isset($_POST['telefono']) && isset($_POST['mensaje'])) {
              $desde = 'From:' . $_POST['email'];

              $comentario = "Nombre: " . $_POST['nombre'] . "\n";
              $comentario = "Apellido: " . $_POST['apellido'] . "\n";
              $comentario .= "Email: " . $_POST['email'] . "\n";
              $comentario .= "Telefono: " . $_POST['telefono'] . "\n";
              $comentario .= "Consulta: " . $_POST['mensaje'] . "\n";

              if (mail($destino, $asunto, $comentario, $desde)) {
                echo '<div class="alert alert-success">¡Mensaje enviado correctamente!</div>';
              } else {
                echo "Hubo un error al enviar su consulta. Por favor, inténtelo más tarde.";
              }
            } else {
              echo "Por favor, complete todos los campos del formulario.";
            }
          }
          ?>

          <form method="POST" action="">
            <div class="row mb-3">
              <div class="col-md-6">
                <input type="text" class="form-control form-control-lg" name="nombre" placeholder="Nombre" required>
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control form-control-lg" name="apellido" placeholder="Apellido" required>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <input type="email" class="form-control form-control-lg" name="email" placeholder="Email" required>
              </div>
              <div class="col-md-6">
                <input type="tel" class="form-control form-control-lg" name="telefono" placeholder="Teléfono">
              </div>
            </div>

            <div class="mb-3">
              <textarea class="form-control form-control-lg" name="mensaje" rows="6" placeholder="Mensaje" required></textarea>
            </div>

            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter">
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