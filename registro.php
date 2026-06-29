<?php 
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';
if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

if(!empty($_POST["Usuario"]) && !empty($_POST["contraseña"]) && !empty($_POST["correo"])){

$usuario = $_POST["Usuario"];
$contraseña = $_POST["contraseña"];
$correo = $_POST["correo"];
$admin = 0;
$ubicacion = 1;
$sql1 = "SELECT nombre_usuario, correo FROM usuarios where nombre_usuario = ? OR correo = ?";
$stmt = mysqli_prepare($conexion, $sql1);
mysqli_stmt_bind_param($stmt, "ss", $usuario, $correo);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$fila = mysqli_fetch_assoc($res);
if($fila && $usuario == $fila['nombre_usuario'] && $correo == $fila['correo']){

echo json_encode(["error" => "el usuario ya existe", "msj" => "el usuario ya existe"]);
exit;
}else{

$sql1 = "INSERT INTO usuarios (nombre_usuario, correo, contrasenia, admin, UbicacionId) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conexion, $sql1);
mysqli_stmt_bind_param($stmt, "sssii", $usuario, $correo, $contraseña, $admin, $ubicacion);
if(mysqli_stmt_execute($stmt)){

echo json_encode(["msj" => "Todo bien"]);
            exit();
}else {
            echo json_encode(["error" => "Fallo la consulta", "msj" => "Fallo la consulta"]);
        }
        
    }
    $stmt->close();
    $conexion->close();
}
else{
    echo json_encode([
        "error" => "Faltan datos"
    ]);
}


?>