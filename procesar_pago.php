<?php

//CUANDO PAGUES
//http://localhost/fratelli-sin-gluten/views/exito.php?external_reference=AQU_EL_ID_DEL_PEDIDO
session_start();

// Cargamos la configuración y la conexión una sola vez
require_once __DIR__ . '/config/config.php';

// 1. Verificar si el usuario ha iniciado sesión y si la petición es POST
$idUsuario = $_SESSION['user_id'] ?? null;

if (!isset($idUsuario) || empty($idUsuario) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: views/pagar.php');
    exit;
}

// 2. Recoger y limpiar los datos de envío del formulario
$ubicacion = trim($_POST['ubicacion'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$casa_depto = trim($_POST['casa_departamento'] ?? '');
$piso = trim($_POST['piso'] ?? '');

// Validar campos obligatorios de la ubicación
if (empty($ubicacion) || empty($numero) || empty($casa_depto)) {
    echo "<script>alert('Por favor, completa todos los campos obligatorios de la dirección.'); window.history.back();</script>";
    exit();
}

// --- NUEVO: Asignar el ID de usuario a la variable global de MySQL para el Trigger ---
$stmt_var = mysqli_prepare($conexion, "SET @id_usuario = ?");
mysqli_stmt_bind_param($stmt_var, "i", $idUsuario);
mysqli_stmt_execute($stmt_var);
// ---------------------------------------------------------------------------------

// 3. Verificar si el usuario ya tiene una ubicación idéntica registrada
$sql_verificar = "SELECT UbicacionID FROM ubicaciones WHERE ubicacion = ? AND numero = ? AND casa_departamento = ? LIMIT 1";
$stmt_ver = mysqli_prepare($conexion, $sql_verificar);
mysqli_stmt_bind_param($stmt_ver, "sss", $ubicacion, $numero, $casa_depto);
mysqli_stmt_execute($stmt_ver);
$res_ver = mysqli_stmt_get_result($stmt_ver);

if ($row_ubicacion = mysqli_fetch_assoc($res_ver)) {
    $nuevo_ubicacion_id = $row_ubicacion['UbicacionID'];
    
    // Como la ubicación ya existe, el trigger de INSERT no se disparará. 
    // Actualizamos manualmente al usuario para asegurar que se le enlace esta dirección reutilizada.
    $sql_act = "UPDATE usuarios SET UbicacionID = ? WHERE UsuarioID = ?";
    $stmt_act = mysqli_prepare($conexion, $sql_act);
    mysqli_stmt_bind_param($stmt_act, "ii", $nuevo_ubicacion_id, $idUsuario);
    mysqli_stmt_execute($stmt_act);

} else {
    // Si no existe, la insertamos. Al hacer el INSERT, el Trigger se ejecutará automáticamente 
    // filtrando por @id_usuario y actualizará únicamente al usuario logueado.
    $sql_ubicacion = "INSERT INTO ubicaciones (ubicacion, casa_departamento, numero, piso) VALUES (?, ?, ?, ?)";
    $stmt_ubi = mysqli_prepare($conexion, $sql_ubicacion);
    mysqli_stmt_bind_param($stmt_ubi, "ssss", $ubicacion, $casa_depto, $numero, $piso);
    
    if (!mysqli_stmt_execute($stmt_ubi)) {
        die("Error al guardar la ubicación: " . mysqli_error($conexion));
    }
}

// 4. Buscar el pedido activo (estado = 0) del usuario
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

// 5. Obtener los detalles y verificar el stock actual de cada producto
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
    if ($itemDet['cantidad'] > $itemDet['stock_producto']) {
        echo "<h3 style='color:red;'>Transacción cancelada</h3>";
        echo "<p>Lo sentimos, no hay suficiente stock para el producto: <strong>" . htmlspecialchars($itemDet['nombre_producto']) . "</strong>. Stock disponible: {$itemDet['stock_producto']}.</p>";
        echo "<a href='index.php?page=pagar'>Volver al carrito</a>";
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

// 6. Configurar tu Access Token de Mercado Pago
$accessToken = 'APP_USR-3012748563648786-101414-5ad925c95df47b5237a1d5881066419f-3765911272'; 

$preferenceData = [
    "items" => $itemsArray,
    //"back_urls" => array(
        //"success" => "http://localhost/fratelli-sin-gluten/views/exito.php",
      //  "failure" => "http://localhost/fratelli-sin-gluten/views/fallo.php",
    //    "pending" => "http://localhost/fratelli-sin-gluten/views/pendiente.php"
  //  ),
   // "auto_return" => "approved",
    "external_reference" => (string)$pedidoId 
];

// 7. Enviar solicitud a Mercado Pago mediante cURL
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

// 8. Redirigir a Mercado Pago si se generó correctamente la preferencia
if ($httpCode === 201 && isset($respuestaMp['init_point'])) {
    header("Location: " . $respuestaMp['init_point']);
    exit;
} else {
    echo "<h3>Error de Mercado Pago (Código HTTP: $httpCode)</h3>";
    echo "<pre>";
    print_r($respuestaMp);
    echo "</pre>";
}
?>