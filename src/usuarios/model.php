<?php
include_once __DIR__ . '/../../config/database.php';

function registrarUsuario($conn, $email, $password, $tipoUsuario)
{
    $claveHash = password_hash($password, PASSWORD_DEFAULT);
    $categoriaCliente = null;
    $estado = "pendiente";

    if ($tipoUsuario == "cliente") {
        $categoriaCliente = "Inicial";
    }

    $token = bin2hex(random_bytes(16));

    $sql = "INSERT INTO usuarios (nombreUsuario, claveUsuario, tipoUsuario, categoriaCliente, estado, token)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $email, $claveHash, $tipoUsuario, $categoriaCliente, $estado, $token);

    if ($stmt->execute()) {
        return [
            "success" => true,
            "id" => $stmt->insert_id,
            "token" => $token
        ];
    } else {
        return [
            "success" => false,
            "error" => $conn->error
        ];
    }
}

function validarUsuarioPorToken($conn, $token)
{
    $sql = "UPDATE usuarios SET estado = 'validado', token = NULL WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();

    return $stmt->affected_rows > 0;
}

function loginUsuario($conn, $email, $password)
{
    $sql = "SELECT * FROM usuarios WHERE nombreUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($password, $usuario['claveUsuario'])) {
            if ($usuario['estado'] !== 'validado') {
                return [
                    "success" => false,
                    "error" => "Cuenta no validada aún."
                ];
            }

            $_SESSION['codUsuario'] = $usuario['codUsuario'];
            $_SESSION['tipoUsuario'] = $usuario['tipoUsuario'];
            $_SESSION['categoriaCliente'] = $usuario['categoriaCliente'];

            return [
                "success" => true,
                "usuario" => $usuario
            ];
        } else {
            return [
                "success" => false,
                "error" => "Contraseña incorrecta."
            ];
        }
    } else {
        return [
            "success" => false,
            "error" => "Usuario no encontrado."
        ];
    }
}

// Duenos de locales

function getDuenosPendientes($conn)
{
    $sql = "SELECT codUsuario, nombreUsuario, estado 
            FROM usuarios 
            WHERE tipoUsuario = 'dueño de local' AND estado = 'pendiente'";
    return $conn->query($sql);
}

function aprobarDuenoLocal($conn, $idUsuario)
{
    $sql = "UPDATE usuarios SET estado = 'validado' WHERE codUsuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    return $stmt->execute();
}

function rechazarDuenoLocal($conn, $idUsuario)
{
    $sql = "DELETE FROM usuarios WHERE codUsuario = ? AND tipoUsuario = 'dueño de local'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    return $stmt->execute();
}

function validarUsuario($conn, $id_usuario)
{
    $sql = "UPDATE usuarios SET estado = 'validado' WHERE codUsuario = $id_usuario";

    $resultado = $conn->query($sql);

    if ($resultado) {
        return true;
    } else {
        return "Error: " . $conn->error;
    }
}

function getUserById($conn, $id_usuario)
{
    $sql = "SELECT * FROM usuarios WHERE codUsuario = $id_usuario";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function getAllUsuarios($conn)
{
    $sql = "SELECT * FROM usuarios ORDER BY codUsuario ASC";
    $result = $conn->query($sql);

    if ($result) {
        return $result;
    } else {
        return "Error: " . $conn->error;
    }
}

function actualizarCategoriaCliente($conn, $codCliente)
{
    $sql = "SELECT COUNT(*) as total 
            FROM uso_promociones
            WHERE codCliente = ? 
              AND estado = 'aceptada' 
              AND fechaUsoPromo >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codCliente);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $total = $result['total'];

    if ($total >= 15) {
        $nuevaCategoria = 'Premium';
    } elseif ($total >= 5) {
        $nuevaCategoria = 'Medium';
    } else {
        $nuevaCategoria = 'Inicial';
    }

    $sqlUpdate = "UPDATE usuarios SET categoriaCliente=? WHERE codUsuario=?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("si", $nuevaCategoria, $codCliente);
    $stmt->execute();

    return $nuevaCategoria;
}
