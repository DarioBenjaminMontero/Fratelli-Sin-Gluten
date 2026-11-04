<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// Verificar permisos de administrador
if (!isset($_SESSION['Rol']) || $_SESSION['Rol'] != 1) {
    header('Location: ../views/index.php?page=admin');
    exit;
}

$usuarioID = (int)($_POST['usuario_id'] ?? 0);
$productos = $_POST['productos'] ?? [];
$cantidades = $_POST['cantidades'] ?? [];

if ($usuarioID <= 0 || empty($productos)) {
    echo "<script>alert('Faltan datos obligatorios para crear el pedido.'); window.history.back();</script>";
    exit;
}

// Iniciar transacción para asegurar consistencia
mysqli_begin_transaction($conexion);

try {
    // 1. Crear el registro principal del pedido (estado = 1 para mostrador / completado automático)
    $q_pedido = "INSERT INTO pedidos (UsuarioID, estado) VALUES (?, 1)";
    $stmt_p = mysqli_prepare($conexion, $q_pedido);
    mysqli_stmt_bind_param($stmt_p, "i", $usuarioID);
    mysqli_stmt_execute($stmt_p);
    
    $pedidoID = mysqli_insert_id($conexion);

    // 2. Recorrer cada producto seleccionado
    for ($i = 0; $i < count($productos); $i++) {
        $productoID = (int)$productos[$i];
        $cantidad = (int)$cantidades[$i];

        if ($productoID > 0 && $cantidad > 0) {
            // Insertar en detallepedido
            $q_det = "INSERT INTO detallepedido (PedidoID, ProductoID, cantidad) VALUES (?, ?, ?)";
            $stmt_d = mysqli_prepare($conexion, $q_det);
            mysqli_stmt_bind_param($stmt_d, "iii", $pedidoID, $productoID, $cantidad);
            mysqli_stmt_execute($stmt_d);

            // Descontar el stock automáticamente del producto
            $q_stock = "UPDATE productos SET stock_producto = stock_producto - ? WHERE ProductoID = ?";
            $stmt_s = mysqli_prepare($conexion, $q_stock);
            mysqli_stmt_bind_param($stmt_s, "ii", $cantidad, $productoID);
            mysqli_stmt_execute($stmt_s);
        }
    }

    // Confirmar transacción
    mysqli_commit($conexion);

    // Redireccionar con parámetro de tiempo para evitar caché
    header('Location: ../views/index.php?page=admin&subpage=pedidos&t=' . time());
    exit;

} catch (Exception $e) {
    // Si algo falla, revertir todo
    mysqli_rollback($conexion);
    echo "Error al procesar el pedido de mostrador: " . $e->getMessage();
}
?>