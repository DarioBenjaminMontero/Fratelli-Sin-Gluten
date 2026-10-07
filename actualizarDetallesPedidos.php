<?php

session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';
$idUsuario = $_POST["id"];
$productoID = $_POST["productoID"];
$cantidad = $_POST["cantidad"];
$precio = $_POST["precio"];
$sql = "SELECT PedidoID FROM pedidos WHERE UsuarioID = ? AND estado = 0;";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if($pedido = mysqli_fetch_assoc($res)){
    $pedidoID = $pedido["PedidoID"];
$sql2 = "INSERT INTO detallepedido (PedidoID, ProductoID, cantidad, precio) VALUES(?,?,?, ?);";
$stmt = mysqli_prepare($conexion, $sql2);
mysqli_stmt_bind_param($stmt, "iiid", $pedidoID, $productoID, $cantidad, $precio);
mysqli_stmt_execute($stmt);
echo json_encode(["mensaje" => "joya"]);
}
else{
$sql3 = "INSERT INTO pedidos (FechaPedido, UsuarioID, estado) VALUES (NOW(), ?, 0);";
$stmt = mysqli_prepare($conexion, $sql3);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$sql4 = "SELECT PedidoID FROM pedidos WHERE UsuarioID = ? AND estado = 0;";
$stmt = mysqli_prepare($conexion, $sql4);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if($pedido = mysqli_fetch_assoc($res)){
    $pedidoID = $pedido["PedidoID"];
$sql5 = "INSERT INTO detallepedido (PedidoID, ProductoID, cantidad, precio) VALUES(?,?,?, ?);";
$stmt = mysqli_prepare($conexion, $sql5);
mysqli_stmt_bind_param($stmt, "iiid", $pedidoID, $productoID, $cantidad, $precio);
mysqli_stmt_execute($stmt);
echo json_encode(["mensaje" => "joya"]);
}
}
?>