<?php
session_start();
require_once __DIR__ . '/config/config.php';

$idUsuario = $_SESSION['user_id'] ?? null;

if (!isset($idUsuario) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: views/pagar.php');
    exit;
}

// 1. Buscar el pedido activo (estado = 0) del usuario
$sqlPedido = "SELECT p.PedidoID FROM pedidos p WHERE p.UsuarioID = ? AND p.estado = 0 LIMIT 1";
$stmt = mysqli_prepare($conexion, $sqlPedido);
mysqli_stmt_bind_param($stmt, "i", $idUsuario);
mysqli_stmt_execute($stmt);
$resPed = mysqli_stmt_get_result($stmt);
$rowPed = mysqli_fetch_assoc($resPed);

if (!$rowPed) {
    echo "No hay un pedido activo para procesar.";
    exit;
}

$pedidoId = $rowPed['PedidoID'];

// 2. Obtener los detalles y verificar el stock actual de cada producto
$sqlDetalles = "SELECT dp.cantidad, pr.ProductoID, pr.nombre_producto, pr.precio, pr.stock_producto 
                FROM detallepedido dp 
                JOIN productos pr ON dp.ProductoID = pr.ProductoID 
                WHERE dp.PedidoID = ?";

$stmtDet = mysqli_prepare($conexion, $sqlDetalles);
mysqli_stmt_bind_param($stmtDet, "i", $pedidoId);
mysqli_stmt_execute($stmtDet);
$resDet = mysqli_stmt_get_result($stmtDet);

$itemsArray = [];
while ($itemDet = mysqli_fetch_assoc($resDet)) {
    // Verificación de stock: Si lo que pide el usuario supera el stock disponible, cancelamos
    if ($itemDet['cantidad'] > $itemDet['stock_producto']) {
        echo "<h3 style='color:red;'>Transacción cancelada</h3>";
        echo "<p>Lo sentimos, no hay suficiente stock para el producto: <strong>" . htmlspecialchars($itemDet['nombre_producto']) . "</strong>. Stock disponible: {$itemDet['stock_producto']}.</p>";
        echo "<a href='views/pagar.php'>Volver al carrito</a>";
        exit;
    }

    $itemsArray[] = [
        "title" => $itemDet['nombre_producto'],
        "quantity" => (int)$itemDet['cantidad'],
        "unit_price" => (float)$itemDet['precio']
    ];
}

if (empty($itemsArray)) {
    echo "Tu carrito está vacío.";
    exit;
}

// 3. Configurar tu Access Token de Mercado Pago
$accessToken = 'APP_USR-3012748563648786-101414-5ad925c95df47b5237a1d5881066419f-3765911272'; 

$preferenceData = [
    "items" => $itemsArray,
    "back_urls" => [
        "success" => "http://localhost/frattellisingluten/views/exito.php",
        "failure" => "http://localhost/frattellisingluten/views/fallo.php",
        "pending" => "http://localhost/frattellisingluten/views/pendiente.php"
    ],
    // Opcional: enviamos el ID del pedido en el external_reference para rastrearlo luego
    "external_reference" => (string)$pedidoId 
];

// 4. Enviar solicitud a Mercado Pago mediante cURL
$ch = curl_init('https://api.mercadopago.com/checkout/preferences');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $accessToken,
    'X-Idempotency-Key: ' . uniqid('mp_', true)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$respuestaMp = json_decode($response, true);

if ($httpCode === 201 && isset($respuestaMp['init_point'])) {
    header("Location: " . $respuestaMp['init_point']);
    exit;
} else {
    echo "<h3>Error de Mercado Pago (Código HTTP: $httpCode)</h3>";
    echo "<pre>";
    print_r($respuestaMp);
    echo "</pre>";
}