<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';
if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

if (!empty($_POST["Usuario"]) && !empty($_POST["contraseña"])) {
    $usuario = $_POST["Usuario"];
    $contraseña = $_POST["contraseña"];
    $query = "SELECT * FROM usuarios WHERE nombre_usuario = ? AND contrasenia = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ss", $usuario, $contraseña);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION['user_id'] = $user['UsuarioID'];
        $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
        $_SESSION['Rol'] = $user['admin'];
        echo json_encode(["success" => true, "user" => $user]); 
    } else {
        echo json_encode(["error" => "Usuario o contraseña incorrectos"]);
    }
    mysqli_close($conexion);
} else {
    echo json_encode(["error" => "Faltan datos requeridos"]);
}