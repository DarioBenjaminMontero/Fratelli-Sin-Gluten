<?php
require_once __DIR__ . '/../config/config.php';

$pedidoId = $_GET['id'] ?? null;

if ($pedidoId) {
    // 1. Verificar el estado actual del pedido para no descontar stock dos veces por error
    $sql_check = "SELECT estado FROM pedidos WHERE PedidoID = ?";
    $stmt_c = mysqli_prepare($conexion, $sql_check);
    mysqli_stmt_bind_param($stmt_c, "i", $pedidoId);
    mysqli_stmt_execute($stmt_c);
    $res_c = mysqli_stmt_get_result($stmt_c);
    $row_c = mysqli_fetch_assoc($res_c);

    if ($row_c && $row_c['estado'] == 0) {
        // 2. Marcar el pedido como completado (estado = 1)
        $sql_update = "UPDATE pedidos SET estado = 1 WHERE PedidoID = ?";
        $stmt_u = mysqli_prepare($conexion, $sql_update);
        mysqli_stmt_bind_param($stmt_u, "i", $pedidoId);
        mysqli_stmt_execute($stmt_u);

        // 3. Descontar el stock de los productos del detalle de este pedido
        $sql_detalles = "SELECT ProductoID, cantidad FROM detallepedido WHERE PedidoID = ?";
        $stmt_d = mysqli_prepare($conexion, $sql_detalles);
        mysqli_stmt_bind_param($stmt_d, "i", $pedidoId);
        mysqli_stmt_execute($stmt_d);
        $res_d = mysqli_stmt_get_result($stmt_d);

        while ($det = mysqli_fetch_assoc($res_d)) {
            $prodId = $det['ProductoID'];
            $cant = $det['cantidad'];

            $sql_stock = "UPDATE productos SET stock_producto = stock_producto - ? WHERE ProductoID = ?";
            $stmt_s = mysqli_prepare($conexion, $sql_stock);
            mysqli_stmt_bind_param($stmt_s, "ii", $cant, $prodId);
            mysqli_stmt_execute($stmt_s);
        }
    }
}

// Redirigir de vuelta al listado de pedidos en el panel de administración
header('Location: ../views/index.php?page=admin&subpage=pedidos');
exit;