<?php

require_once __DIR__ . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($conexion)) {
    echo json_encode(['ok' => false, 'mensaje' => 'Error de conexión a la base de datos']);
    exit;
}

// Añadimos el filtro para traer solo los productos que NO estén borrados
$sql = "SELECT ProductoID, nombre_producto, precio, stock_producto, imagen FROM productos WHERE esta_borrado = 0";
$resultado = mysqli_query($conexion, $sql);

$productos = [];

if ($resultado) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        $productos[] = [
            'id' => $row['ProductoID'],
            'nombre' => $row['nombre_producto'],
            'precio' => $row['precio'],
            'stock' => $row['stock_producto'],
            'imagen' => $row['imagen'] ?? 'producto-ejemplo.jpg'
        ];
    }
    echo json_encode(['ok' => true, 'productos' => $productos]);
} else {
    echo json_encode(['ok' => false, 'mensaje' => 'Error en la consulta SQL']);
}