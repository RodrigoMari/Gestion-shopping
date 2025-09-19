<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '/../../../src/usuarios/model.php';

$duenosPendientes = getDuenosPendientes($conn);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Dueños Locales - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>
  <?php include '../../../includes/header.php'; ?>

  <main class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>public/admin/index.php" class="text-decoration-none">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dueños Locales</li>
      </ol>
    </nav>

    <!-- Título -->
    <div class="row mb-4">
      <div class="col-md-8">
        <h2 class="fw-bold text-dark">Administración de Dueños Locales</h2>
        <p class="text-muted">Aprueba o rechaza las cuentas de los dueños de locales registrados.</p>
      </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-5">
      <div class="col-md-3">
        <div class="card text-center shadow-sm border-0">
          <div class="card-body">
            <i class="fas fa-user-clock fa-2x text-warning mb-2"></i>
            <h4 class="fw-bold"><?= $duenosPendientes ? $duenosPendientes->num_rows : 0 ?></h4>
            <p class="text-muted mb-0">Pendientes de Aprobación</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla de dueños -->
    <div class="card shadow-sm border-0">
      <div class="card-header bg-light">
        <h5 class="mb-0 fw-bold">Lista de Dueños Pendientes</h5>
      </div>
      <div class="card-body p-0">
        <?php if ($duenosPendientes && $duenosPendientes->num_rows > 0): ?>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th class="fw-semibold">ID</th>
                  <th class="fw-semibold">Email</th>
                  <th class="fw-semibold">Estado</th>
                  <th class="fw-semibold text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php while ($row = $duenosPendientes->fetch_assoc()): ?>
                  <tr>
                    <td class="fw-semibold text-primary">#<?= $row['codUsuario'] ?></td>
                    <td><?= htmlspecialchars($row['nombreUsuario']) ?></td>
                    <td>
                      <span class="badge bg-warning text-dark"><?= $row['estado'] ?></span>
                    </td>
                    <td class="text-center">
                      <form method="POST" action="<?= SRC_URL ?>usuarios/aprobar_duenos.php" class="d-inline">
                        <input type="hidden" name="id_usuario" value="<?= $row['codUsuario'] ?>">
                        <button type="submit" name="accion" value="aprobar" class="btn btn-sm btn-success" title="Aprobar">
                          <i class="fas fa-check"></i> Aprobar
                        </button>
                      </form>
                      <form method="POST" action="<?= SRC_URL ?>usuarios/aprobar_duenos.php" class="d-inline">
                        <input type="hidden" name="id_usuario" value="<?= $row['codUsuario'] ?>">
                        <button type="submit" name="accion" value="rechazar" class="btn btn-sm btn-danger" title="Rechazar" onclick="return confirm('¿Seguro que quieres rechazar este usuario?')">
                          <i class="fas fa-times"></i> Rechazar
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No hay dueños pendientes</h5>
            <p class="text-muted">Cuando un dueño de local se registre, aparecerá aquí para validación.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <?php include '../../../includes/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>