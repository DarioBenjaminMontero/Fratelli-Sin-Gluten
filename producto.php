<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';

if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

$productoID = $_POST["productoID"];

$sql = "SELECT nombre_producto, precio, imagen, descripcion, alergenos FROM productos WHERE ProductoID = ?;";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $productoID);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
if ($producto = mysqli_fetch_assoc($res)) {


    $sqlIngredientes = "SELECT i.nombre_ingrediente, p.cantidad_ingredientes 
                        FROM preparaciones p 
                        INNER JOIN ingredientes i ON p.IngredienteID = i.IngredienteID 
                        WHERE p.ProductoID = ?";

    $stmtIng = mysqli_prepare($conexion, $sqlIngredientes);
    mysqli_stmt_bind_param($stmtIng, "i", $productoID);
    mysqli_stmt_execute($stmtIng);
    $resIng = mysqli_stmt_get_result($stmtIng);

    $ingredientes = [];
    while ($fila = mysqli_fetch_assoc($resIng)) {
        $ingredientes[] = $fila;
    }

   
    $producto['ingredientes'] = $ingredientes;

    echo json_encode($producto);

} else {
    echo json_encode(["error" => "Producto no encontrado"]);
}

mysqli_stmt_close($stmt);
mysqli_close($conexion);
?>