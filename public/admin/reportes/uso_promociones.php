<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/promociones/model.php';

$usoResumen = getUsoPromocionesResumen($conn);
$topPromos = getTopPromocionesMasUsadas($conn, 5);
$usoMensual = getUsoPromocionesMensual($conn, 6);
$usoPorLocal = getUsoPromocionesPorLocal($conn);

$aceptadas = $usoResumen['porEstado']['aceptada'] ?? 0;
$rechazadas = $usoResumen['porEstado']['rechazada'] ?? 0;
$pendientes = ($usoResumen['porEstado']['pendiente'] ?? 0) + ($usoResumen['porEstado']['enviada'] ?? 0);
$totalSolicitudes = $usoResumen['totalSolicitudes'] ?? 0;
$clientesUnicos = $usoResumen['clientesUnicos'] ?? 0;
$conversion = $totalSolicitudes > 0 ? round(($aceptadas / $totalSolicitudes) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes de descuentos - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../../css/style.css">
</head>

<body>
  <div class="d-flex">
    <?php include '../../../includes/flash_toast.php'; ?>
    <?php include '../../../includes/sidebar.php'; ?>

    <div class="flex-grow-1">
      <?php include '../../../includes/admin_header.php'; ?>

      <main class="admin-main">
        <div class="container-fluid">
          <div class="row mb-4 align-items-center">
            
            <div class="col-md-8">
              <h1 class="h3">Uso de descuentos</h1>
              <p class="text-muted mb-0">Seguimiento de solicitudes y redenciones de promociones por parte de los clientes.</p>
            </div>

            <div class="col-md-4 text-md-end">
              <a href="reporte_uso_promociones.php" class="btn btn-warning">
                <i class="fas fa-plus me-2"></i>Generador de reportes
              </a>
            </div>

          </div>
          
          <div class="row g-4 mb-5">
            <div class="col-12 col-md-6 col-xl-3">
              <div class="card admin-card stats-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Solicitudes registradas</h6>
                      <h3 class="mb-0"><?= number_format($totalSolicitudes) ?></h3>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-receipt"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
              <div class="card admin-card stats-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Promos efectivizadas</h6>
                      <h3 class="mb-0"><?= number_format($aceptadas) ?></h3>
                      <small class="text-success">Tasa de conversion <?= $conversion ?>%</small>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-check-circle"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
              <div class="card admin-card stats-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Solicitudes pendientes</h6>
                      <h3 class="mb-0"><?= number_format($pendientes) ?></h3>
                      <small class="text-warning">Revisar con los locales</small>
                      <div class="small text-muted">Rechazadas <?= number_format($rechazadas) ?></div>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-hourglass-half"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
              <div class="card admin-card stats-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <h6 class="text-muted mb-2">Clientes unicos</h6>
                      <h3 class="mb-0"><?= number_format($clientesUnicos) ?></h3>
                      <small class="text-muted">Clientes que usaron al menos una promo</small>
                    </div>
                    <div class="card-icon bg-light rounded-circle p-3">
                      <i class="fas fa-users"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row g-4 mb-5">
            <div class="col-lg-7">
              <div class="card admin-card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                      <h5 class="card-title mb-1">Promociones mas utilizadas</h5>
                      <p class="text-muted mb-0">Ranking por redenciones aceptadas.</p>
                    </div>
                    <span class="badge bg-warning text-dark">Top 5</span>
                  </div>
                  <?php if (!empty($topPromos)): ?>
                    <div class="table-responsive">
                      <table class="table align-middle mb-0">
                        <thead class="table-light">
                          <tr>
                            <th>Promocion</th>
                            <th>Local</th>
                            <th class="text-center">Aceptadas</th>
                            <th class="text-center">Pendientes</th>
                            <th class="text-center">Rechazadas</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($topPromos as $promo): ?>
                            <tr>
                              <td class="fw-semibold"><?= htmlspecialchars($promo['textoPromo']) ?></td>
                              <td><?= htmlspecialchars($promo['nombreLocal']) ?></td>
                              <td class="text-center text-success fw-semibold"><?= (int) ($promo['totalAceptadas'] ?? 0) ?></td>
                              <td class="text-center text-warning"><?= (int) ($promo['totalPendientes'] ?? 0) ?></td>
                              <td class="text-center text-muted"><?= (int) ($promo['totalRechazadas'] ?? 0) ?></td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php else: ?>
                    <p class="text-muted mb-0">Aun no hay solicitudes registradas.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-lg-5">
              <div class="card admin-card h-100">
                <div class="card-body">
                  <h5 class="card-title">Tendencia mensual</h5>
                  <p class="text-muted">Ultimos <?= count($usoMensual) ?> meses con actividad.</p>
                  <?php if (!empty($usoMensual)): ?>
                    <ul class="list-group list-group-flush">
                      <?php foreach ($usoMensual as $periodo): ?>
                        <?php
                        $label = $periodo['periodo'] ?? '';
                        $fecha = DateTime::createFromFormat('Y-m', $label);
                        if ($fecha instanceof DateTime) {
                          $label = $fecha->format('M Y');
                        }
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <div>
                            <span class="fw-semibold"><?= htmlspecialchars($label) ?></span>
                            <div class="small text-muted">Aceptadas: <?= (int) ($periodo['totalAceptadas'] ?? 0) ?></div>
                          </div>
                          <span class="badge bg-light text-dark border">
                            <?= (int) ($periodo['totalSolicitudes'] ?? 0) ?> solicitudes
                          </span>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  <?php else: ?>
                    <p class="text-muted mb-0">No hay actividad reciente para mostrar.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <div class="card admin-card mb-5">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <h5 class="card-title mb-1">Locales con mayor uso</h5>
                  <p class="text-muted mb-0">Solicitudes de promociones agrupadas por local.</p>
                </div>
              </div>
              <?php if (!empty($usoPorLocal)): ?>
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Local</th>
                        <th class="text-center">Solicitudes</th>
                        <th class="text-center">Aceptadas</th>
                        <th class="text-center">Rechazadas</th>
                        <th class="text-center">Conversion</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($usoPorLocal as $local): ?>
                        <?php
                        $totalLocal = (int) ($local['totalSolicitudes'] ?? 0);
                        $aceptadasLocal = (int) ($local['totalAceptadas'] ?? 0);
                        $rechazadasLocal = (int) ($local['totalRechazadas'] ?? 0);
                        $conversionLocal = $totalLocal > 0 ? round(($aceptadasLocal / $totalLocal) * 100, 1) : 0;
                        ?>
                        <tr>
                          <td class="fw-semibold"><?= htmlspecialchars($local['nombreLocal']) ?></td>
                          <td class="text-center fw-semibold"><?= $totalLocal ?></td>
                          <td class="text-center text-success"><?= $aceptadasLocal ?></td>
                          <td class="text-center text-muted"><?= $rechazadasLocal ?></td>
                          <td class="text-center">
                            <span class="badge bg-warning text-dark"><?= $conversionLocal ?>%</span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php else: ?>
                <p class="text-muted mb-0">Aun no hay locales con solicitudes registradas.</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>