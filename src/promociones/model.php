<?php
include_once __DIR__ . '/../../config/database.php';

function borrarPromocion($conn, $id_promocion)
{
    $sql1 = "DELETE FROM uso_promociones WHERE codPromo = $id_promocion";
    $conn->query($sql1);

    $sql2 = "DELETE FROM promociones WHERE codPromo = $id_promocion";
    if ($conn->query($sql2) === TRUE) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function getAllPromociones($conn, $limit = 10, $offset = 0)
{
    $sql = "SELECT p.*, l.nombreLocal 
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            ORDER BY p.fechaDesdePromo DESC
            LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

function contarTodasPromociones($conn)
{
    $sql = "SELECT COUNT(*) as total FROM promociones";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}

function getPromocionesPendientes($conn)
{
    $sql = "SELECT p.*, l.nombreLocal
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'pendiente'";
    return $conn->query($sql);
}


function getPromocionesPorNivel($conn, $categoriaCliente = null)
{
    $sqlBase = "SELECT p.codPromo, p.textoPromo, p.fechaDesdePromo, p.fechaHastaPromo, 
                   p.categoriaCliente, p.diasSemana,
                   l.nombreLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'aprobada'";

    if ($categoriaCliente === null) {
        $sql = $sqlBase;
        $stmt = $conn->prepare($sql);
    } else {
        $niveles = [];
        switch ($categoriaCliente) {
            case 'Inicial':
                $niveles = ['Inicial'];
                break;
            case 'Medium':
                $niveles = ['Inicial', 'Medium'];
                break;
            case 'Premium':
                $niveles = ['Inicial', 'Medium', 'Premium'];
                break;
        }

        $placeholders = implode(',', array_fill(0, count($niveles), '?'));
        $sql = $sqlBase . " AND (p.categoriaCliente IN ($placeholders) OR p.categoriaCliente IS NULL)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($niveles)), ...$niveles);
    }

    $stmt->execute();
    return $stmt->get_result();
}

function solicitarPromocion($conn, $codCliente, $codPromo)
{
    $fechaHoy = date('Y-m-d');

    $sqlCheck = "SELECT * FROM uso_promociones WHERE codCliente=? AND codPromo=? AND fechaUsoPromo=?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param("iis", $codCliente, $codPromo, $fechaHoy);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return ["success" => false, "error" => "Ya solicitaste esta promocion hoy."];
    }

    $sql = "INSERT INTO uso_promociones (codCliente, codPromo, fechaUsoPromo, estado) 
            VALUES (?, ?, ?, 'enviada')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $codCliente, $codPromo, $fechaHoy);

    if ($stmt->execute()) {
        return ["success" => true];
    } else {
        return ["success" => false, "error" => $conn->error];
    }
}

function getPromocionesActivasCount($conn)
{
    $sql = "SELECT COUNT(*) AS total FROM promociones WHERE estadoPromo = 'aprobada' AND fechaHastaPromo >= CURDATE()";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total'];
    } else {
        return "Error: " . $conn->error;
    }
}

function validarPromocion($conn, $id_promocion, $opcion)
{
    if ($opcion === "aprobar") {
        $sql = "UPDATE promociones SET estadoPromo = 'aprobada' WHERE codPromo = ?";
    } else {
        $sql = "UPDATE promociones SET estadoPromo = 'denegada' WHERE codPromo = ?";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_promocion);
    return $stmt->execute();
}


function getPromocionesDestacadas($conn)
{
    $sql = "SELECT p.textoPromo, p.fechaHastaPromo, l.nombreLocal, p.categoriaCliente,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'aprobada' AND p.fechaHastaPromo >= CURDATE()
            ORDER BY p.fechaHastaPromo ASC
            LIMIT 3";
    return $conn->query($sql);
}

function getPromocionesPorLocal($conn, $codLocal, $limit = 12, $offset = 0)
{
    $sql = "SELECT p.*, l.nombreLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.codLocal = ? AND p.estadoPromo = 'aprobada'
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $codLocal, $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

function contarPromocionesPorLocal($conn, $codLocal)
{
    $sql = "SELECT COUNT(*) as total
            FROM promociones p
            WHERE p.codLocal = ? AND p.estadoPromo = 'aprobada'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codLocal);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row['total'];
}

function buscarPromociones($conn, $termino)
{
    $termino = trim($termino);
    $terminoLike = '%' . $termino . '%';

    $sql = "SELECT p.*, l.nombreLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'aprobada'
              AND (p.textoPromo LIKE ? OR l.nombreLocal LIKE ? OR l.rubroLocal LIKE ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $terminoLike, $terminoLike, $terminoLike);
    $stmt->execute();
    return $stmt->get_result();
}
// Duenos locales

function getPromoById($conn, $codPromo)
{
    $sql = "SELECT p.*, l.nombreLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.codPromo = ? AND p.estadoPromo = 'aprobada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codPromo);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function filtrarPromociones($conn, $texto = '', $rubro = '', $categoriaCliente = null, $limit = 12, $offset = 0)
{
    $sql = "SELECT p.*, l.nombreLocal, l.rubroLocal,
                   CONCAT('https://placehold.co/600x400?text=', l.nombreLocal) AS imagenUrl
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'aprobada'";

    $params = [];
    $types = "";

    if (!empty($texto)) {
        $texto = trim($texto);
        $textoLike = '%' . $texto . '%';
        $sql .= " AND (p.textoPromo LIKE ? OR l.nombreLocal LIKE ?)";
        $types .= "ss";
        $params[] = $textoLike;
        $params[] = $textoLike;
    }

    if (!empty($rubro)) {
        $sql .= " AND l.rubroLocal = ?";
        $types .= "s";
        $params[] = $rubro;
    }

    if ($categoriaCliente !== null) {
        $niveles = [];
        switch ($categoriaCliente) {
            case 'Inicial':
                $niveles = ['Inicial'];
                break;
            case 'Medium':
                $niveles = ['Inicial', 'Medium'];
                break;
            case 'Premium':
                $niveles = ['Inicial', 'Medium', 'Premium'];
                break;
        }
        if (!empty($niveles)) {
            $placeholders = implode(',', array_fill(0, count($niveles), '?'));
            $sql .= " AND (p.categoriaCliente IN ($placeholders) OR p.categoriaCliente IS NULL)";
            $types .= str_repeat('s', count($niveles));
            foreach ($niveles as $nivel) $params[] = $nivel;
        }
    }

    $sql .= " ORDER BY p.fechaDesdePromo DESC LIMIT ? OFFSET ?";
    $types .= "ii";
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

function contarPromocionesFiltradas($conn, $texto = '', $rubro = '', $categoriaCliente = null)
{
    $sql = "SELECT COUNT(*) as total
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE p.estadoPromo = 'aprobada'";

    $params = [];
    $types = "";

    if (!empty($texto)) {
        $texto = trim($texto);
        $textoLike = '%' . $texto . '%';
        $sql .= " AND (p.textoPromo LIKE ? OR l.nombreLocal LIKE ?)";
        $types .= "ss";
        $params[] = $textoLike;
        $params[] = $textoLike;
    }

    if (!empty($rubro)) {
        $sql .= " AND l.rubroLocal = ?";
        $types .= "s";
        $params[] = $rubro;
    }

    if ($categoriaCliente !== null) {
        $niveles = [];
        switch ($categoriaCliente) {
            case 'Inicial':
                $niveles = ['Inicial'];
                break;
            case 'Medium':
                $niveles = ['Inicial', 'Medium'];
                break;
            case 'Premium':
                $niveles = ['Inicial', 'Medium', 'Premium'];
                break;
        }
        if (!empty($niveles)) {
            $placeholders = implode(',', array_fill(0, count($niveles), '?'));
            $sql .= " AND (p.categoriaCliente IN ($placeholders) OR p.categoriaCliente IS NULL)";
            $types .= str_repeat('s', count($niveles));
            foreach ($niveles as $nivel) $params[] = $nivel;
        }
    }

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row['total'];
}

function contarUsoPromocion($conn, $codPromo)
{
    $sql = "SELECT COUNT(*) as total FROM uso_promociones WHERE codPromo=? AND estado='aceptada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codPromo);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'];
}

function getSolicitudesPendientes($conn, $codLocal)
{
    $sql = "SELECT u.codCliente, u.codPromo, u.fechaUsoPromo, us.nombreUsuario, p.textoPromo
            FROM uso_promociones u
            JOIN usuarios us ON u.codCliente = us.codUsuario
            JOIN promociones p ON u.codPromo = p.codPromo
            WHERE p.codLocal=? AND u.estado IN ('pendiente','enviada')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codLocal);
    $stmt->execute();
    return $stmt->get_result();
}

// Cliente
function getSolicitudesPorCliente($conn, $codCliente)
{
    $sql = "SELECT u.estado AS estadoSolicitud, u.fechaUsoPromo, 
                   p.textoPromo, l.nombreLocal
            FROM uso_promociones u
            JOIN promociones p ON u.codPromo = p.codPromo
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE u.codCliente = ?
            ORDER BY u.fechaUsoPromo DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codCliente);
    $stmt->execute();
    return $stmt->get_result();
}

function contarPromosUsadasPorCliente($conn, $codCliente)
{
    $sql = "SELECT COUNT(*) as total 
            FROM uso_promociones 
            WHERE codCliente = ? AND estado = 'aceptada'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codCliente);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['total'] ?? 0;
}
function getUsoPromocionesResumen($conn)
{
    $resumen = [
        'totalSolicitudes' => 0,
        'clientesUnicos' => 0,
        'porEstado' => []
    ];

    $sqlTotales = "SELECT COUNT(*) AS totalSolicitudes, COUNT(DISTINCT codCliente) AS clientesUnicos FROM uso_promociones";
    $resultTotales = $conn->query($sqlTotales);

    if ($resultTotales instanceof mysqli_result) {
        $totales = $resultTotales->fetch_assoc();
        $resumen['totalSolicitudes'] = (int) ($totales['totalSolicitudes'] ?? 0);
        $resumen['clientesUnicos'] = (int) ($totales['clientesUnicos'] ?? 0);
        $resultTotales->free();
    }

    $sqlEstados = "SELECT estado, COUNT(*) AS total FROM uso_promociones GROUP BY estado";
    $resultEstados = $conn->query($sqlEstados);

    if ($resultEstados instanceof mysqli_result) {
        while ($row = $resultEstados->fetch_assoc()) {
            $estado = $row['estado'] ?? 'desconocido';
            $resumen['porEstado'][$estado] = (int) $row['total'];
        }
        $resultEstados->free();
    }

    return $resumen;
}

function getTopPromocionesMasUsadas($conn, $limit = 5)
{
    $limit = max(1, (int) $limit);
    $sql = "SELECT p.codPromo, p.textoPromo, l.nombreLocal,
                   COUNT(u.codPromo) AS totalSolicitudes,
                   SUM(CASE WHEN u.estado = 'aceptada' THEN 1 ELSE 0 END) AS totalAceptadas,
                   SUM(CASE WHEN u.estado = 'rechazada' THEN 1 ELSE 0 END) AS totalRechazadas,
                   SUM(CASE WHEN u.estado IN ('pendiente', 'enviada') THEN 1 ELSE 0 END) AS totalPendientes
            FROM promociones p
            JOIN locales l ON p.codLocal = l.codLocal
            LEFT JOIN uso_promociones u ON p.codPromo = u.codPromo
            GROUP BY p.codPromo, p.textoPromo, l.nombreLocal
            HAVING totalSolicitudes > 0
            ORDER BY totalAceptadas DESC, totalSolicitudes DESC
            LIMIT $limit";

    $result = $conn->query($sql);

    if (!($result instanceof mysqli_result)) {
        return [];
    }

    $promociones = [];
    while ($row = $result->fetch_assoc()) {
        $promociones[] = $row;
    }
    $result->free();

    return $promociones;
}

function getUsoPromocionesMensual($conn, $months = 6)
{
    $months = max(1, (int) $months);
    $sql = "SELECT DATE_FORMAT(fechaUsoPromo, '%Y-%m') AS periodo,
                   COUNT(*) AS totalSolicitudes,
                   SUM(CASE WHEN estado = 'aceptada' THEN 1 ELSE 0 END) AS totalAceptadas
            FROM uso_promociones
            WHERE fechaUsoPromo >= DATE_SUB(CURDATE(), INTERVAL $months MONTH)
            GROUP BY DATE_FORMAT(fechaUsoPromo, '%Y-%m')
            ORDER BY periodo ASC";

    $result = $conn->query($sql);

    if (!($result instanceof mysqli_result)) {
        return [];
    }

    $periodos = [];
    while ($row = $result->fetch_assoc()) {
        $periodos[] = $row;
    }
    $result->free();

    return $periodos;
}

function getUsoPromocionesPorLocal($conn)
{
    $sql = "SELECT l.codLocal, l.nombreLocal,
                   COUNT(u.codPromo) AS totalSolicitudes,
                   SUM(CASE WHEN u.estado = 'aceptada' THEN 1 ELSE 0 END) AS totalAceptadas,
                   SUM(CASE WHEN u.estado = 'rechazada' THEN 1 ELSE 0 END) AS totalRechazadas
            FROM locales l
            JOIN promociones p ON p.codLocal = l.codLocal
            LEFT JOIN uso_promociones u ON u.codPromo = p.codPromo
            GROUP BY l.codLocal, l.nombreLocal
            HAVING totalSolicitudes > 0
            ORDER BY totalSolicitudes DESC";

    $result = $conn->query($sql);

    if (!($result instanceof mysqli_result)) {
        return [];
    }

    $locales = [];
    while ($row = $result->fetch_assoc()) {
        $locales[] = $row;
    }
    $result->free();

    return $locales;
}

function getClientesConUsoPromociones($conn)
{
    $sql = "SELECT DISTINCT u.codCliente, us.nombreUsuario 
            FROM uso_promociones u
            JOIN usuarios us ON u.codCliente = us.codUsuario
            ORDER BY us.nombreUsuario ASC";
    $result = $conn->query($sql);
    if (!($result instanceof mysqli_result)) {
        return [];
    }
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
    $result->free();
    return $clientes;
}

function getPromocionesUsadas($conn)
{
    $sql = "SELECT DISTINCT u.codPromo, p.textoPromo 
            FROM uso_promociones u
            JOIN promociones p ON u.codPromo = p.codPromo
            ORDER BY p.textoPromo ASC";
    $result = $conn->query($sql);
    if (!($result instanceof mysqli_result)) {
        return [];
    }
    $promociones = [];
    while ($row = $result->fetch_assoc()) {
        $promociones[] = $row;
    }
    $result->free();
    return $promociones;
}

/**
 * Genera el reporte de uso de promociones con filtros dinámicos.
 * (Versión actualizada para filtrar por LOCAL)
 * * @param mysqli $conn La conexión a la base de datos.
 * @param array $filtros Un array asociativo con los filtros:
 * 'clientes' (array de codCliente),
 * 'locales' (array de codLocal),  <-- MODIFICADO
 * 'estado' (string),
 * 'fecha_desde' (string 'YYYY-MM-DD'),
 * 'fecha_hasta' (string 'YYYY-MM-DD').
 * @return array Los resultados del reporte.
 */
function getReporteUsoPromociones($conn, $filtros)
{
    $sql = "SELECT up.codCliente, up.codPromo, up.fechaUsoPromo, up.estado,
                   u.nombreUsuario AS nombreCliente,
                   p.textoPromo,
                   l.nombreLocal
            FROM uso_promociones up
            JOIN usuarios u ON up.codCliente = u.codUsuario
            JOIN promociones p ON up.codPromo = p.codPromo
            JOIN locales l ON p.codLocal = l.codLocal
            WHERE 1=1"; // Cláusula WHERE base

    $params = [];
    $types = "";

    // Filtrar por clientes
    if (!empty($filtros['clientes']) && is_array($filtros['clientes'])) {
        $placeholders = implode(',', array_fill(0, count($filtros['clientes']), '?'));
        $sql .= " AND up.codCliente IN ($placeholders)";
        $types .= str_repeat('i', count($filtros['clientes']));
        $params = array_merge($params, $filtros['clientes']);
    }

    // --- INICIO DE MODIFICACIÓN ---
    // Filtrar por locales (en lugar de promociones)
    if (!empty($filtros['locales']) && is_array($filtros['locales'])) {
        $placeholders = implode(',', array_fill(0, count($filtros['locales']), '?'));
        // Filtramos por p.codLocal (de la tabla promociones)
        $sql .= " AND p.codLocal IN ($placeholders)";
        $types .= str_repeat('i', count($filtros['locales']));
        $params = array_merge($params, $filtros['locales']);
    }
    // --- FIN DE MODIFICACIÓN ---

    // Filtrar por estado
    if (!empty($filtros['estado'])) {
        $sql .= " AND up.estado = ?";
        $types .= "s";
        $params[] = $filtros['estado'];
    }

    // Filtrar por fecha desde
    if (!empty($filtros['fecha_desde'])) {
        $sql .= " AND up.fechaUsoPromo >= ?";
        $types .= "s";
        $params[] = $filtros['fecha_desde'];
    }

    // Filtrar por fecha hasta
    if (!empty($filtros['fecha_hasta'])) {
        $sql .= " AND up.fechaUsoPromo <= ?";
        $types .= "s";
        $params[] = $filtros['fecha_hasta'];
    }

    $sql .= " ORDER BY up.fechaUsoPromo DESC";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("Error al preparar la consulta: " . $conn->error);
        return [];
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if (!($result instanceof mysqli_result)) {
        return [];
    }

    $reporte = [];
    while ($row = $result->fetch_assoc()) {
        $reporte[] = $row;
    }
    $result->free();
    $stmt->close();

    return $reporte;
}

function getLocalesConPromociones($conn)
{
    $sql = "SELECT DISTINCT l.codLocal, l.nombreLocal 
            FROM locales l
            JOIN promociones p ON l.codLocal = p.codLocal
            ORDER BY l.nombreLocal ASC";

    $result = $conn->query($sql);
    if (!($result instanceof mysqli_result)) {
        return [];
    }
    $locales = [];
    while ($row = $result->fetch_assoc()) {
        $locales[] = $row;
    }
    $result->free();
    return $locales;
}
