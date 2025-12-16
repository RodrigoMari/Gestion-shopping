<?php
require_once __DIR__ . '../../../../config/database.php';
require_once __DIR__ . '/../../../src/usuarios/model.php';

$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$porPagina = 10;
$offset = ($pagina - 1) * $porPagina;

$duenosPendientes = getAllDuenos($conn, $porPagina, $offset);
$totalRegistros = contarTodosDuenos($conn);
$totalPaginas = ceil($totalRegistros / $porPagina);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Dueños Locales - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>
  <div class="d-flex admin-layout">
    <?php include '../../../includes/flash_toast.php'; ?>
    <?php include '../../../includes/sidebar.php'; ?>
    <div class="flex-grow-1 admin-content">
      <?php include '../../../includes/admin_header.php'; ?>
      <main class="container-fluid my-4 my-md-5 px-2 px-md-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PUBLIC_URL ?>admin/dashboard.php" class="text-decoration-none">Admin</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dueños Locales</li>
          </ol>
        </nav>

        <!-- Título -->
        <div class="row mb-4">
          <div class="col-12 col-md-8">
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
                <h4 class="fw-bold"><?= $duenosPendientes->num_rows ?></h4>
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
                          <?php
                          $estado = $row['estado'];
                          $badgeClass = '';
                          if ($estado == 'pendiente') {
                            $badgeClass = 'bg-warning text-dark';
                          } elseif ($estado == 'validado') {
                            $badgeClass = 'bg-success';
                          } elseif ($estado == 'rechazado') {
                            $badgeClass = 'bg-danger';
                          }
                          ?>
                          <span class="badge <?= $badgeClass ?>"><?= $estado ?></span>
                        </td>
                        <td class="text-center">
                          <?php if ($row['estado'] == 'pendiente'): ?>
                            <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
                              <form method="POST" action="<?= SRC_URL ?>usuarios/aprobar_duenos.php" class="m-0">
                                <input type="hidden" name="id_usuario" value="<?= $row['codUsuario'] ?>">
                                <button type="submit" name="accion" value="aprobar" class="btn btn-sm btn-success" title="Aprobar">
                                  <i class="fas fa-check"></i> Aprobar
                                </button>
                              </form>
                              <form method="POST" action="<?= SRC_URL ?>usuarios/aprobar_duenos.php" class="m-0">
                                <input type="hidden" name="id_usuario" value="<?= $row['codUsuario'] ?>">
                                <button type="submit" name="accion" value="rechazar" class="btn btn-sm btn-danger" title="Rechazar" onclick="return confirm('¿Seguro que quieres rechazar este usuario?')">
                                  <i class="fas fa-times"></i> Rechazar
                                </button>
                              </form>
                            </div>
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
              <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Navegación de páginas" class="mt-4">
                  <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                      <a class="page-link" href="?pagina=<?= $pagina - 1 ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                      <li class="page-item <?= ($pagina == $i) ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                      <a class="page-link" href="?pagina=<?= $pagina + 1 ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
                <p class="text-center text-muted small mt-2">
                  Mostrando <?= $duenosPendientes->num_rows ?> de <?= $totalRegistros ?> dueños
                </p>
              <?php endif; ?>
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
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>