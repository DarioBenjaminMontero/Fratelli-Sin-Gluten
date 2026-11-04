<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Verificar permisos de administrador
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 1) {
    header('Location: ../views/index.php?page=admin');
    exit;
}

$pedidoID = (int)($_GET['id'] ?? 0);

if ($pedidoID > 0) {
    // 1. Verificar el estado actual del pedido para evitar descontar stock dos veces
    $q_verificar = "SELECT estado FROM pedidos WHERE PedidoID = ?";
    $stmt_v = mysqli_prepare($conexion, $q_verificar);
    mysqli_stmt_bind_param($stmt_v, "i", $pedidoID);
    mysqli_stmt_execute($stmt_v);
    $res_v = mysqli_stmt_get_result($stmt_v);
    $pedido = mysqli_fetch_assoc($res_v);

    // Solo procedemos si el pedido existe y está pendiente (estado = 0)
    if ($pedido && $pedido['estado'] == 0) {
        
        // Iniciamos una transacción para asegurar que o se descuenta todo o no se cambia nada
        mysqli_begin_transaction($conexion);

        try {
            // 2. Obtener los productos y cantidades del detalle de este pedido
            $q_det = "SELECT ProductoID, cantidad FROM detallepedido WHERE PedidoID = ?";
            $stmt_d = mysqli_prepare($conexion, $q_det);
            mysqli_stmt_bind_param($stmt_d, "i", $pedidoID);
            mysqli_stmt_execute($stmt_d);
            $r_det = mysqli_stmt_get_result($stmt_d);

            while ($item = mysqli_fetch_assoc($r_det)) {
                $prodID = (int)$item['ProductoID'];
                $cant = (int)$item['cantidad'];

                // 3. Descontar el stock en la tabla productos
                $q_stock = "UPDATE productos SET stock_producto = stock_producto - ? WHERE ProductoID = ?";
                $stmt_s = mysqli_prepare($conexion, $q_stock);
                mysqli_stmt_bind_param($stmt_s, "ii", $cant, $prodID);
                mysqli_stmt_execute($stmt_s);
            }

            // 4. Cambiar el estado del pedido a Completado (1)
            $q_upd = "UPDATE pedidos SET estado = 1 WHERE PedidoID = ?";
            $stmt_u = mysqli_prepare($conexion, $q_upd);
            mysqli_stmt_bind_param($stmt_u, "i", $pedidoID);
            mysqli_stmt_execute($stmt_u);

            // Confirmar cambios en la base de datos
            mysqli_commit($conexion);

        } catch (Exception $e) {
            // Si algo falla, revertimos los cambios
            mysqli_rollback($conexion);
        }
    }
}

header('Location: ../views/index.php?page=admin&subpage=pedidos');
exit;
?>