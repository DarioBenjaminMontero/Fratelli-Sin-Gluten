<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$idUsuario = $_SESSION['user_id'] ?? null;

if (!isset($idUsuario)) {
    header('Location: login.php');
    exit;
}

$payment_id = $_GET['payment_id'] ?? null;
$status = $_GET['status'] ?? null;
$external_reference = $_GET['external_reference'] ?? null; 

// 1. Verificamos que el pago esté aprobado
if ($status === 'approved' || isset($payment_id)) {
    
    // Obtener el ID del pedido asegurándonos de que su estado sea 0 (pendiente)
    if ($external_reference) {
        $pedidoId = intval($external_reference);
        // Verificamos explícitamente que esté en estado 0 para evitar doble procesamiento
        $sqlPed = "SELECT PedidoID FROM pedidos WHERE PedidoID = ? AND UsuarioID = ? AND estado = 0 LIMIT 1";
        $stmtP = mysqli_prepare($conexion, $sqlPed);
        mysqli_stmt_bind_param($stmtP, "ii", $pedidoId, $idUsuario);
    } else {
        $sqlPed = "SELECT PedidoID FROM pedidos WHERE UsuarioID = ? AND estado = 0 LIMIT 1";
        $stmtP = mysqli_prepare($conexion, $sqlPed);
        mysqli_stmt_bind_param($stmtP, "i", $idUsuario);
    }

    mysqli_stmt_execute($stmtP);
    $resP = mysqli_stmt_get_result($stmtP);
    
    if ($rowP = mysqli_fetch_assoc($resP)) {
        $pedidoId = $rowP['PedidoID'];

        // A. Obtener los productos y cantidades de ese pedido para descontar el stock
        $sqlItems = "SELECT ProductoID, cantidad FROM detallepedido WHERE PedidoID = ?";
        $stmtItems = mysqli_prepare($conexion, $sqlItems);
        mysqli_stmt_bind_param($stmtItems, "i", $pedidoId);
        mysqli_stmt_execute($stmtItems);
        $resItems = mysqli_stmt_get_result($stmtItems);

        while ($item = mysqli_fetch_assoc($resItems)) {
            $prodId = $item['ProductoID'];
            $cantVendida = $item['cantidad'];

            // B. Restar el stock en la tabla productos
            $sqlUpdateStock = "UPDATE productos SET stock_producto = stock_producto - ? WHERE ProductoID = ?";
            $stmtStock = mysqli_prepare($conexion, $sqlUpdateStock);
            mysqli_stmt_bind_param($stmtStock, "ii", $cantVendida, $prodId);
            mysqli_stmt_execute($stmtStock);
        }

        // C. Actualizar el estado del pedido a 1 (pagado / finalizado) para que no se vuelva a procesar
        $sqlUpdPedido = "UPDATE pedidos SET estado = 1 WHERE PedidoID = ?";
        $stmtUpd = mysqli_prepare($conexion, $sqlUpdPedido);
        mysqli_stmt_bind_param($stmtUpd, "i", $pedidoId);
        mysqli_stmt_execute($stmtUpd);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Compra Exitosa!</title>
</head>
<body>
    <div style="text-align: center; margin-top: 50px;">
        <h1>¡Muchas gracias por tu compra!</h1>
        <p>Tu pago ha sido procesado y registrado con éxito.</p>
        <?php if ($payment_id): ?>
            <p>ID de pago de Mercado Pago: <strong><?= htmlspecialchars($payment_id) ?></strong></p>
        <?php endif; ?>
        <br>
        <a href="http://localhost/fratelli-sin-gluten/views/index.php?page=main" style="padding: 10px 20px; background: #009ee3; color: white; text-decoration: none; border-radius: 5px;">Volver a la tienda</a>
    </div>
</body>
</html>