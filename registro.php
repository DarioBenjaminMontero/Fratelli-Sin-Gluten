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
$sql1 = "SELECT nombre_usuario, correo FROM usuarios where nombre_usuario = ? AND correo = ?";
$stmt = mysqli_prepare($conexion, $sql1);
mysqli_stmt_bind_param($stmt, "ss", $usuario, $correo);
mysqli_stmt_execute($stmt);
$fila = mysqli_fetch_assoc($res);
}

if($fila && $usuario == $fila['nombre_usuario'] && $correo = $fila['correo']){

echo json_encode(["error" => "el usuario ya existe", "msj" => "el usuario ya existe"]);

}else{

$sql1 = "INSERT INTO usuario ()";

}
?>