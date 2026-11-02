<?php
session_start();
require_once __DIR__ . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');

$idUsuario = $_SESSION['user_id'] ?? null;  

if (!isset($idUsuario)) {
    echo json_encode(['ok' => false, 'error' => 'Inicia sesión.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$accion = $_GET['accion'] ?? null;
$productoId = $_GET['id'] ?? null;

if (!$accion || !$productoId) {
    echo json_encode(['ok' => false, 'error' => 'Faltan parámetros.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$sqlPedido = "SELECT PedidoID FROM pedidos WHERE UsuarioID = ? AND estado = 0 LIMIT 1";
$stmtPed = mysqli_prepare($conexion, $sqlPedido);
mysqli_stmt_bind_param($stmtPed, "i", $idUsuario);
mysqli_stmt_execute($stmtPed);
$resPed = mysqli_stmt_get_result($stmtPed);

if ($rowPed = mysqli_fetch_assoc($resPed)) {
    $pedidoId = $rowPed['PedidoID'];
    
    if ($accion === 'aumentar') {
        $sqlUpd = "UPDATE detallepedido SET cantidad = cantidad + 1 WHERE PedidoID = ? AND ProductoID = ?";
        $stmtUpd = mysqli_prepare($conexion, $sqlUpd);
        mysqli_stmt_bind_param($stmtUpd, "ii", $pedidoId, $productoId);
        mysqli_stmt_execute($stmtUpd);
    } elseif ($accion === 'disminuir') {
        $sqlUpd = "UPDATE detallepedido SET cantidad = cantidad - 1 WHERE PedidoID = ? AND ProductoID = ? AND cantidad > 1";
        $stmtUpd = mysqli_prepare($conexion, $sqlUpd);
        mysqli_stmt_bind_param($stmtUpd, "ii", $pedidoId, $productoId);
        mysqli_stmt_execute($stmtUpd);
    } elseif ($accion === 'eliminar') {
        $sqlDel = "DELETE FROM detallepedido WHERE PedidoID = ? AND ProductoID = ?";
        $stmtDel = mysqli_prepare($conexion, $sqlDel);
        mysqli_stmt_bind_param($stmtDel, "ii", $pedidoId, $productoId);
        mysqli_stmt_execute($stmtDel);
    }

    $sqlCount = "SELECT COUNT(*) AS total FROM detallepedido WHERE PedidoID = ?";
    $stmtCount = mysqli_prepare($conexion, $sqlCount);
    mysqli_stmt_bind_param($stmtCount, "i", $pedidoId);
    mysqli_stmt_execute($stmtCount);
    $resCount = mysqli_stmt_get_result($stmtCount);
    $rowCount = mysqli_fetch_assoc($resCount);

    // Si ya no quedan detalles, eliminamos el pedido principal
    if ($rowCount && $rowCount['total'] == 0) {
        $sqlDelPed = "DELETE FROM pedidos WHERE PedidoID = ?";
        $stmtDelPed = mysqli_prepare($conexion, $sqlDelPed);
        mysqli_stmt_bind_param($stmtDelPed, "i", $pedidoId);
        mysqli_stmt_execute($stmtDelPed);
    }

    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['ok' => false, 'error' => 'No se encontró el pedido.'], JSON_UNESCAPED_UNICODE);
}