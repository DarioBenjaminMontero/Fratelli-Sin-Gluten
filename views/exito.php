<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';

$idUsuario = $_SESSION['user_id'] ?? null;
$pedidoId = $_GET['external_reference'] ?? null; // Viene desde Mercado Pago

if ($pedidoId && $idUsuario) {
    // 1. Marcar el pedido como pagado/completado (estado = 1)
    $sql_update_pedido = "UPDATE pedidos SET estado = 1 WHERE PedidoID = ? AND UsuarioID = ?";
    $stmt_p = mysqli_prepare($conexion, $sql_update_pedido);
    mysqli_stmt_bind_param($stmt_p, "ii", $pedidoId, $idUsuario);
    mysqli_stmt_execute($stmt_p);

    // 2. Descontar el stock de los productos comprados en este pedido
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>¡Pago Exitoso! - Fratelli sin Gluten</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Ajusta tu ruta CSS si es necesario -->
    <style>
        .container-pago { max-width: 600px; margin: 60px auto; text-align: center; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .container-pago h1 { color: #16a34a; margin-bottom: 15px; }
        .container-pago p { color: #475569; margin-bottom: 25px; font-size: 1.1rem; }
        .btn-volver { background: #d97706; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .btn-volver:hover { background: #b45309; color: white; }
    </style>
</head>
<body>
    <div class="container-pago">
        <h1>¡Muchas gracias por tu compra!</h1>
        <p>Tu pago se ha procesado con éxito y el pedido ya se encuentra en preparación en nuestra panadería.</p>
        <?php if ($pedidoId): ?>
            <p style="font-size: 0.9rem; color: #94a3b8;">Número de Referencia del Pedido: #<?php echo htmlspecialchars($pedidoId); ?></p>
        <?php endif; ?>
        <br>
        <a href="index.php?page=main" class="btn-volver">Volver al Inicio</a>
    </div>
</body>
</html>