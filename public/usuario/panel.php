<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/promociones/model.php';
require_once __DIR__ . '/../../src/usuarios/model.php';

if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'cliente') {
  die("Acceso denegado.");
}

$codUsuario = $_SESSION['codUsuario'];
$cliente = getUserById($conn, $codUsuario);
$solicitudes = getSolicitudesPorCliente($conn, $codUsuario);
$totalUsadas = contarPromosUsadasPorCliente($conn, $codUsuario);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Usuario - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
  <?php include '../../includes/header.php'; ?>

  <main class="container my-5 flex-grow-1">
    <h2 class="mb-4">Bienvenido, <?= htmlspecialchars($cliente['nombreUsuario']) ?></h2>

    <!-- Datos del cliente -->
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fas fa-user fa-2x text-primary mb-2"></i>
            <h5 class="fw-bold"><?= ucfirst($cliente['tipoUsuario']) ?></h5>
            <p class="text-muted mb-0">Rol</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fas fa-star fa-2x text-warning mb-2"></i>
            <h5 class="fw-bold"><?= $cliente['categoriaCliente'] ?></h5>
            <p class="text-muted mb-0">Categoria</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fas fa-ticket-alt fa-2x text-success mb-2"></i>
            <h5 class="fw-bold"><?= $totalUsadas ?></h5>
            <p class="text-muted mb-0">Promociones Usadas</p>
          </div>
        </div>
      </div>
    </div>

    <?php
    $rangos = [
      'Inicial' => 5,
      'Medium' => 10,
      'Premium' => 15
    ];

    $maxNivel = $rangos[$cliente['categoriaCliente']] ?? 5;
    $progreso = min(100, intval(($totalUsadas / $maxNivel) * 100));
    ?>
    <div class="card shadow-sm mb-5">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Progreso de Categoria</h5>
        <div class="progress" style="height: 25px;">
          <div class="progress-bar 
        <?php if ($cliente['categoriaCliente'] === 'Inicial') echo 'bg-info'; ?>
        <?php if ($cliente['categoriaCliente'] === 'Medium') echo 'bg-warning'; ?>
        <?php if ($cliente['categoriaCliente'] === 'Premium') echo 'bg-success'; ?>"
            role="progressbar" style="width: <?= $progreso ?>%;"
            aria-valuenow="<?= $progreso ?>" aria-valuemin="0" aria-valuemax="100">
            <?= $progreso ?>%
          </div>
        </div>
        <p class="mt-2 mb-0">
          Has usado <strong><?= $totalUsadas ?></strong> promociones.
          <?php if ($cliente['categoriaCliente'] !== 'Premium'): ?>
            Te faltan <strong><?= max(0, $maxNivel - $totalUsadas) ?></strong> para alcanzar la categoria <strong>
              <?= ($cliente['categoriaCliente'] === 'Inicial') ? 'Medium' : 'Premium' ?>
            </strong>.
          <?php else: ?>
            ¡Ya estas en la categoria mas alta!
          <?php endif; ?>
        </p>
      </div>
    </div>

    <!-- Solicitudes de promociones -->
    <div class="card mb-5">
      <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Mis Solicitudes de Promociones</h5>
      </div>
      <div class="card-body">
        <?php if ($solicitudes && $solicitudes->num_rows > 0): ?>
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Promocion</th>
                <th>Local</th>
                <th>Fecha Solicitud</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($s = $solicitudes->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($s['textoPromo']) ?></td>
                  <td><?= htmlspecialchars($s['nombreLocal']) ?></td>
                  <td><?= $s['fechaUsoPromo'] ?></td>
                  <td>
                    <?php if ($s['estadoSolicitud'] === 'aceptada'): ?>
                      <span class="badge bg-success">Aceptada</span>
                    <?php elseif (in_array($s['estadoSolicitud'], ['pendiente', 'enviada'])): ?>
                      <span class="badge bg-warning text-dark">Pendiente</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Rechazada</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No has solicitado promociones todavia.</p>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include '../../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>