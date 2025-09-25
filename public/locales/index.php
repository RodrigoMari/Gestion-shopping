<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/promociones/model.php';
require_once __DIR__ . '/../../src/usuarios/model.php';

if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'dueno de local') {
  die("Acceso denegado.");
}

$codUsuario = $_SESSION['codUsuario'];

$sqlLocal = "SELECT * FROM locales WHERE codUsuario = ?";
$stmt = $conn->prepare($sqlLocal);
$stmt->bind_param("i", $codUsuario);
$stmt->execute();
$local = $stmt->get_result()->fetch_assoc();

if (!$local) {
  die("No tienes un local asignado. Contacta al administrador.");
}

$codLocal = $local['codLocal'];
$promociones = getPromocionesPorLocal($conn, $codLocal);
$solicitudes = getSolicitudesPendientes($conn, $codLocal);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Local - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/style.css">
</head>

<body class="flex-column min-vh-100">
  <?php include '../../includes/flash_toast.php'; ?>
  <?php include '../../includes/header.php'; ?>

  <main class="container my-5 flex-grow-1">
    <h2 class="mb-4">Panel del Local: <?= htmlspecialchars($local['nombreLocal']) ?></h2>

    <!-- Botón para crear promo -->
    <a href="create.php" class="btn btn-warning mb-4"><i class="fas fa-plus"></i> Crear Promoción</a>

    <!-- Promociones -->
    <div class="card mb-5">
      <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Mis Promociones</h5>
      </div>
      <div class="card-body">
        <?php if ($promociones && $promociones->num_rows > 0): ?>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Texto</th>
                <th>Vigencia</th>
                <th>Categoría</th>
                <th>Clientes que usaron</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($p = $promociones->fetch_assoc()): ?>
                <tr>
                  <td>#<?= $p['codPromo'] ?></td>
                  <td><?= htmlspecialchars($p['textoPromo']) ?></td>
                  <td><?= $p['fechaDesdePromo'] ?> - <?= $p['fechaHastaPromo'] ?></td>
                  <td><?= $p['categoriaCliente'] ?></td>
                  <td><?= contarUsoPromocion($conn, $p['codPromo']) ?></td>
                  <td>
                    <form method="POST" action="<?= SRC_URL ?>promociones/delete.php" class="d-inline">
                      <input type="hidden" name="id_promo" value="<?= $p['codPromo'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No tienes promociones cargadas.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Solicitudes -->
    <div class="card">
      <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Solicitudes Pendientes</h5>
      </div>
      <div class="card-body">
        <?php if ($solicitudes && $solicitudes->num_rows > 0): ?>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Cliente</th>
                <th>Promoción</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($s = $solicitudes->fetch_assoc()): ?>
                <tr>
                  <td><?= $s['nombreUsuario'] ?></td>
                  <td><?= $s['textoPromo'] ?></td>
                  <td><?= $s['fechaUsoPromo'] ?></td>
                  <td>
                    <form method="POST" action="<?= SRC_URL ?>promociones/solicitudes.php" class="d-inline">
                      <input type="hidden" name="codCliente" value="<?= $s['codCliente'] ?>">
                      <input type="hidden" name="codPromo" value="<?= $s['codPromo'] ?>">
                      <button type="submit" name="accion" value="aceptar" class="btn btn-sm btn-success">Aceptar</button>
                    </form>
                    <form method="POST" action="<?= SRC_URL ?>promociones/solicitudes.php" class="d-inline">
                      <input type="hidden" name="codCliente" value="<?= $s['codCliente'] ?>">
                      <input type="hidden" name="codPromo" value="<?= $s['codPromo'] ?>">
                      <button type="submit" name="accion" value="rechazar" class="btn btn-sm btn-danger">Rechazar</button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p>No hay solicitudes pendientes.</p>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include '../../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>