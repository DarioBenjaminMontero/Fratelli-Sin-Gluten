<?php 
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';

if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}

$categoria = $_POST["categoria"];

$query = "SELECT idCategoria FROM categorias WHERE nombreCategoria = ?;";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "s", $categoria);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
if ($fila = mysqli_fetch_assoc($resultado)) {
    $idCategoria = $fila['idCategoria'];   
}

$query = "SELECT productos.ProductoID,productos.imagen, productos.nombre_producto, productos.stock_producto, productos.precio, productos.subCategoria, subcategorias.nombreSubCategoria FROM productos INNER JOIN subcategorias ON productos.subCategoria = subcategorias.idSubCategoria WHERE productos.categoria = ?;";
$stmt = mysqli_prepare($conexion, $query);
mysqli_stmt_bind_param($stmt, "i", $idCategoria);
mysqli_stmt_execute($stmt);
$resultado2 = mysqli_stmt_get_result($stmt);

$listaProductos = [];

while($fila = mysqli_fetch_assoc($resultado2)){
$listaProductos[] = $fila;
}

echo json_encode($listaProductos);
exit;
?>