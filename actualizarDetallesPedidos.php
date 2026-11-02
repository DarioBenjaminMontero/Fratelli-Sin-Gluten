<?php

session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';
$idUsuario = $_POST["id"];
$productoID = $_POST["productoID"];
$cantidad = $_POST["cantidad"];
$precio = $_POST["precio"];
function sacarPedido($conexion, $idUsuario)
{
    $sql = "SELECT PedidoID FROM pedidos WHERE UsuarioID = ? AND estado = 0;";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    return (mysqli_stmt_get_result($stmt));
}
function insertarDetallePedido($conexion, $pedidoID, $productoID, $precio, $cantidad)
{
    $sql2 = "INSERT INTO detallepedido (PedidoID, ProductoID, cantidad, precio) VALUES(?,?,?,?);";
    $stmt = mysqli_prepare($conexion, $sql2);
    mysqli_stmt_bind_param($stmt, "iiid", $pedidoID, $productoID, $cantidad, $precio);
    mysqli_stmt_execute($stmt);
    echo json_encode(["mensaje" => "joya"]);
}

function comprobarExistencia($conexion, $productoID, $idUsuario)
{

    $sql = "SELECT ProductoId FROM detallepedido INNER JOIN pedidos ON detallepedido.PedidoID = pedidos.PedidoID WHERE detallepedido.ProductoId = ? AND pedidos.estado = 0 AND pedidos.UsuarioID = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $productoID, $idUsuario);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);

}

$res = sacarPedido($conexion, $idUsuario);
if ($pedido = mysqli_fetch_assoc($res)) {
    $pedidoID = $pedido["PedidoID"];

    $res = comprobarExistencia($conexion, $productoID, $idUsuario);
    if (mysqli_num_rows($res) != 0) {
        echo json_encode(["mensaje" => "Ya existe el producto en el carrito, si querés aumentarlo, hacelo desde el carrito"]);
    } else {
        insertarDetallePedido($conexion, $pedidoID, $productoID, $precio, $cantidad);
        echo json_encode(["mensaje" => "Exito"]);
    }
} else {
    $sql3 = "INSERT INTO pedidos (FechaPedido, UsuarioID, estado) VALUES (NOW(), ?, 0);";
    $stmt = mysqli_prepare($conexion, $sql3);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $res = sacarPedido($conexion, $idUsuario);

    if ($pedido = mysqli_fetch_assoc($res)) {
        $pedidoID = $pedido["PedidoID"];
        $res = comprobarExistencia($conexion, $productoID, $idUsuario);
        if (mysqli_num_rows($res) != 0) {
            echo json_encode(["mensaje" => "Ya existe el producto en el carrito, si querés aumentarlo, hacelo desde el carrito"]);
        } else {
            insertarDetallePedido($conexion, $pedidoID, $productoID, $precio, $cantidad);
            echo json_encode(["mensaje" => "Exito"]);
        }
    }
}
?>