<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';
if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}
$logout = $_POST["volver"];
if ($logout==true) {
    session_destroy();
    echo json_encode(["success" => true, "message" => "Sesión cerrada correctamente"]);
    exit;
}
else if($logout==false){
$usuario = $_POST["Usuario"];

if (!empty($_POST["contraseña"])&&!empty($_POST["contraseñanueva"])) {
    $contraseña=$_POST["contraseña"];
    $contraseñanueva=$_POST["contraseñanueva"];
    $query = "UPDATE usuarios SET contraseña=? WHERE UsuarioID = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ss", $contraseñanueva, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
}
if(!empty($_POST["ubicacion"])){
     $ubicacion=$_POST["ubicacion"];
     $departamentonumero=$_POST["departamentonumero"];
     $departamentopiso=$_POST["departamentopiso"];
     $query = "SELECT UbicacionID FROM usuarios WHERE UsuarioID = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($result) == 0) {
        if(!empty($_POST["departamentonumero"])&&!empty($_POST["departamentopiso"])){
        $query = "INSERT INTO ubicaciones ( ubicacion, casa_departamento, numero, piso) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssss",  $ubicacion, "departamento", $departamentonumero, $departamentopiso);
        mysqli_stmt_execute($stmt);
        }
        else{
        $query = "INSERT INTO ubicaciones ( ubicacion, casa_departamento, numero, piso) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssss",  $ubicacion, "casa", "", "");
        mysqli_stmt_execute($stmt);
    }
    }
    else{
         $query = "SELECT UbicacionID FROM usuarios WHERE UsuarioID = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $ubicacionID = mysqli_fetch_assoc($result)['UbicacionID'];
    if(!empty($_POST["departamentonumero"])&&!empty($_POST["departamentopiso"])){
        $dep="departamento";
        $query = "UPDATE ubicaciones SET ubicacion=?, casa_departamento=?,numero=?,piso=? WHERE UbicacionID = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $ubicacion, $dep, $departamentonumero, $departamentopiso, $ubicacionID);
    mysqli_stmt_execute($stmt);
    if($stmt==true){
        echo json_encode(["success" => true, "message" => "Ubicación actualizada correctamente"]);
        $_SESSION['ubicacion'] = $ubicacion;
        $_SESSION['casa_departamento'] = "departamento";
        $_SESSION['numero'] = $departamentonumero;
        $_SESSION['piso'] = $departamentopiso;
    } 
    else {
        echo json_encode(["error" => "No se pudo actualizar la ubicación"]);
    }
    }
    else{
       $query = "UPDATE ubicaciones SET ubicacion=?, casa_departamento=? WHERE UbicacionID = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $ubicacion, "casa", $ubicacionID);
    mysqli_stmt_execute($stmt);
    $_SESSION['ubicacion'] = $ubicacion;
    $_SESSION['casa_departamento'] = "casa";
    }
    }
}
}

else {
    echo json_encode(["error" => "Faltan datos requeridos"]);
    mysqli_close($conexion);
}