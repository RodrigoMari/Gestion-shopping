<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/promociones/model.php';
require_once __DIR__ . '/../../../src/usuarios/model.php';

// Obtener listas para los filtros
$clientesDisponibles = getClientesConUsoPromociones($conn);
$localesDisponibles = getLocalesConPromociones($conn); // Asumiendo que esta función existe de nuestro chat anterior

// Parámetros de filtro iniciales (o de la petición GET)
$filtros = [
    'clientes' => $_GET['clientes'] ?? [],
    'locales' => $_GET['locales'] ?? [],
    'estado' => $_GET['estado'] ?? '',
    'fecha_desde' => $_GET['fecha_desde'] ?? '',
    'fecha_hasta' => $_GET['fecha_hasta'] ?? ''
];

$reporteResultados = [];
if (!empty($_GET)) { 
    $reporteResultados = getReporteUsoPromociones($conn, $filtros); // Asumiendo que esta función existe
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reporte de Uso de Promociones - Rosario Center</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  
  <link rel="stylesheet" href="../../../css/style.css"> 
  
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body>
  <div class="d-flex">
    <?php include '../../../includes/flash_toast.php'; ?>
    <?php include '../../../includes/sidebar.php'; ?>

    <div class="flex-grow-1">
      <?php include '../../../includes/admin_header.php'; ?>

      <main class="admin-main">
        <div class="container-fluid">
          <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
              <div>
                <h1 class="h3">Reporte de Uso de Promociones</h1>
                <p class="text-muted mb-0">Genera reportes detallados sobre el uso de promociones con filtros.</p>
              </div>
              <button id="btnExportarPDF" class="btn btn-warning">
                <i class="fas fa-file-pdf me-2"></i>Exportar a PDF
              </button>
            </div>
          </div>

          
          <div class="card admin-card mb-4">
            <div class="card-body">
              <h5 class="card-title mb-3">Filtros de Reporte</h5>
              <form id="filtroForm" action="" method="GET">
                <div class="row g-3">
                  <div class="col-md-6 col-lg-3">
                    <label for="clientes" class="form-label">Clientes</label>
                    <select class="form-select select2-clientes" id="clientes" name="clientes[]" multiple="multiple">
                      <?php foreach ($clientesDisponibles as $cliente): ?>
                        <option value="<?= htmlspecialchars($cliente['codCliente']) ?>"
                                <?= in_array($cliente['codCliente'], $filtros['clientes']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($cliente['nombreUsuario']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="col-md-6 col-lg-3">
                    <label for="locales" class="form-label">Locales</label>
                    <select class="form-select select2-locales" id="locales" name="locales[]" multiple="multiple">
                      <?php foreach ($localesDisponibles as $local): ?>
                        <option value="<?= htmlspecialchars($local['codLocal']) ?>"
                                <?= in_array($local['codLocal'], $filtros['locales']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($local['nombreLocal']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  
                  <div class="col-md-6 col-lg-2">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                      <option value="">Todos</option>
                      <option value="pendiente" <?= ($filtros['estado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
                      <option value="enviada" <?= ($filtros['estado'] == 'enviada') ? 'selected' : '' ?>>Enviada</option>
                      <option value="aceptada" <?= ($filtros['estado'] == 'aceptada') ? 'selected' : '' ?>>Aceptada</option>
                      <option value="rechazada" <?= ($filtros['estado'] == 'rechazada') ? 'selected' : '' ?>>Rechazada</option>
                    </select>
                  </div>
                  <div class="col-md-6 col-lg-2">
                    <label for="fecha_desde" class="form-label">Fecha Desde</label>
                    <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
                  </div>
                  <div class="col-md-6 col-lg-2">
                    <label for="fecha_hasta" class="form-label">Fecha Hasta</label>
                    <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-12 text-end">
                    <button type="submit" class="btn btn-warning me-2">Aplicar Filtros</button>
                    <a href="reporte_uso_promociones.php" class="btn btn-secondary">Limpiar Filtros</a>
                  </div>
                </div>
              </form>
            </div>
          </div>

          
          <div class="card admin-card mb-5" id="reporte-content">
            <div class="card-body">
              <h5 class="card-title mb-3">Resultados del Reporte</h5>
              <?php if (!empty($reporteResultados)): ?>
                <div class="table-responsive">
                  <table class="table align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th>Cod. Cliente</th>
                        <th>Cliente</th>
                        <th>Cod. Promo</th>
                        <th>Promoción</th>
                        <th>Local</th>
                        <th>Fecha Uso</th>
                        <th class="text-center">Estado</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($reporteResultados as $fila): ?>
                        <tr>
                          <td><?= htmlspecialchars($fila['codCliente']) ?></td>
                          <td><?= htmlspecialchars($fila['nombreCliente']) ?></td>
                          <td><?= htmlspecialchars($fila['codPromo']) ?></td>
                          <td><?= htmlspecialchars($fila['textoPromo']) ?></td>
                          <td><?= htmlspecialchars($fila['nombreLocal']) ?></td>
                          <td><?= htmlspecialchars($fila['fechaUsoPromo']) ?></td>
                          <td class="text-center">
                            <?php
                                $estado = htmlspecialchars($fila['estado']);
                                $badgeClass = 'bg-light text-dark border';
                                if ($estado == 'pendiente' || $estado == 'enviada') {
                                    $badgeClass = 'bg-warning text-dark';
                                } elseif ($estado == 'aceptada') {
                                    $badgeClass = 'bg-success text-white';
                                } elseif ($estado == 'rechazada') {
                                    $badgeClass = 'bg-danger text-white';
                                }
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= ucfirst($estado) ?></span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php else: ?>
                 <?php if (empty($_GET)): ?>
                    <p class="text-muted mb-0">Aplica filtros para generar el reporte de uso de promociones.</p>
                 <?php else: ?>
                    <p class="text-muted mb-0">No se encontraron resultados para los filtros aplicados.</p>
                 <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>

        </div>
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Inicializar Select2 para clientes
      $('.select2-clientes').select2({
        placeholder: "Selecciona uno o más clientes",
        allowClear: true
      });

      // Inicializar Select2 para locales
      $('.select2-locales').select2({
        placeholder: "Selecciona uno o más locales",
        allowClear: true
      });

      // Lógica de exportación a PDF (sin cambios)
      document.getElementById('btnExportarPDF').addEventListener('click', function(e) {
        e.preventDefault();

        const { jsPDF } = window.jspdf;
        const element = document.getElementById('reporte-content'); 
        const btn = this;

        btn.disabled = true; 
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generando PDF...';

        html2canvas(element, {
          scale: 2, 
          useCORS: true 
        }).then(function(canvas) {
          const imgData = canvas.toDataURL('image/png');
          const pdf = new jsPDF('p', 'mm', 'a4'); 
          const pdfWidth = pdf.internal.pageSize.getWidth();
          const pdfHeight = pdf.internal.pageSize.getHeight();
          const imgWidth = pdfWidth - 20; 
          const imgHeight = (canvas.height * imgWidth) / canvas.width;
          let position = 10; 
          let heightLeft = imgHeight;
          while (heightLeft > 0) {
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pdfHeight;
            position -= pdfHeight; 
            if (heightLeft > 0) {
              pdf.addPage();
            }
          }
          pdf.save('reporte-uso-promociones.pdf');
          btn.disabled = false; 
          btn.innerHTML = '<i class="fas fa-file-pdf me-2"></i>Exportar a PDF';
        });
      });

    });
  </script>

</body>

</html>