<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';

if (!isset($_SESSION['tipoUsuario']) || $_SESSION['tipoUsuario'] !== 'dueño de local') {
  die("Acceso denegado.");
}

$codUsuario = $_SESSION['codUsuario'];

$sqlLocal = "SELECT * FROM locales WHERE codUsuario = ?";
$stmt = $conn->prepare($sqlLocal);
$stmt->bind_param("i", $codUsuario);
$stmt->execute();
$local = $stmt->get_result()->fetch_assoc();

if (!$local) {
  die("Todavía no tenés un local asignado por el administrador.");
}

$codLocal = $local['codLocal'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $textoPromo = $_POST['textoPromo'];
  $fechaDesde = $_POST['fechaDesdePromo'];
  $fechaHasta = $_POST['fechaHastaPromo'];
  $categoria = $_POST['categoriaCliente'];
  $diaSemana = $_POST['diasSemana'];

  $sql = "INSERT INTO promociones 
            (textoPromo, fechaDesdePromo, fechaHastaPromo, categoriaCliente, diasSemana, estadoPromo, codLocal)
            VALUES (?, ?, ?, ?, ?, 'pendiente', ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ssssii", $textoPromo, $fechaDesde, $fechaHasta, $categoria, $diaSemana, $codLocal);

  if ($stmt->execute()) {
    $mensaje = '<div class="alert alert-success mt-3">Promoción creada correctamente. Ahora debe ser aprobada por el administrador.</div>';
  } else {
    $mensaje = '<div class="alert alert-danger mt-3">Error al crear la promoción: ' . $conn->error . '</div>';
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Crear Promoción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
  <?php include '../../includes/header.php'; ?>

  <main class="container my-5">
    <h2 class="mb-4">Crear Promoción para <?= htmlspecialchars($local['nombreLocal']) ?></h2>

    <form method="POST" class="card shadow-sm p-4">
      <div class="mb-3">
        <label for="textoPromo" class="form-label">Texto de la Promoción</label>
        <input type="text" name="textoPromo" id="textoPromo" class="form-control" maxlength="200" required>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="fechaDesdePromo" class="form-label">Fecha Desde</label>
          <input type="date" name="fechaDesdePromo" id="fechaDesdePromo" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label for="fechaHastaPromo" class="form-label">Fecha Hasta</label>
          <input type="date" name="fechaHastaPromo" id="fechaHastaPromo" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label for="categoriaCliente" class="form-label">Categoría mínima de cliente</label>
        <select name="categoriaCliente" id="categoriaCliente" class="form-select" required>
          <option value="Inicial">Inicial</option>
          <option value="Medium">Medium</option>
          <option value="Premium">Premium</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="diasSemana" class="form-label">Día de la semana</label>
        <select name="diasSemana" id="diasSemana" class="form-select" required>
          <option value="0">Domingo</option>
          <option value="1">Lunes</option>
          <option value="2">Martes</option>
          <option value="3">Miércoles</option>
          <option value="4">Jueves</option>
          <option value="5">Viernes</option>
          <option value="6">Sábado</option>
        </select>
      </div>

      <button type="submit" class="btn btn-warning">Crear Promoción</button>
    </form>

    <?= $mensaje ?>
  </main>

  <?php include '../../includes/footer.php'; ?>
</body>

</html>