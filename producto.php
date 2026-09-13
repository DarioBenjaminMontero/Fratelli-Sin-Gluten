<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';

if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

$productoID = $_POST["productoID"];

$sql = "SELECT nombre_producto, precio, imagen FROM productos WHERE ProductoID = ?;";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $productoID);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
if ($producto = mysqli_fetch_assoc($res)) {
    echo json_encode($producto);
} else {
    echo json_encode(["error" => "Producto no encontrado"]);
}

?>