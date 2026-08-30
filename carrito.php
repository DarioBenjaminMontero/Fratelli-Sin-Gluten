<?php 
session_start();
require_once __DIR__ . '/config/config.php';

$idUsuario = $_SESSION['user_id'] ?? null;  

if (!isset($idUsuario)) {
    echo json_encode([
        'ok' => false,
        'error' => 'Inicia sesión antes de continuar.'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$sql1 = "SELECT 
    p.nombre_producto, 
    dp.cantidad, 
    dp.precio, 
    p.imagen, 
    (dp.cantidad * dp.precio) AS precio_total
FROM detallepedido dp
INNER JOIN pedidos ped ON dp.PedidoID = ped.PedidoID
INNER JOIN productos p ON dp.ProductoID = p.ProductoID
WHERE ped.UsuarioID = ? ";

$stmt = mysqli_prepare($conexion, $sql1);
if (!$stmt) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error al preparar la consulta: ' . mysqli_error($conexion)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (!$res) {
    echo json_encode([
        'ok' => false,
        'error' => 'Error al ejecutar la consulta: ' . mysqli_error($conexion)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}
// Convertimos el resultado de MySQLi en un array asociativo de PHP
$productosCarrito = mysqli_fetch_all($res, MYSQLI_ASSOC);

echo json_encode(['ok' => true,
'productos' => $productosCarrito
],JSON_UNESCAPED_UNICODE);