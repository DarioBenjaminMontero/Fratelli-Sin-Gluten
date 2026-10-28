<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['ok' => false, 'mensaje' => 'ID no proporcionado']);
    exit;
}

// Borrado lógico: Actualizamos el campo en lugar de borrar la fila
$sql = "UPDATE productos SET esta_borrado = 1 WHERE ProductoID = ?";
$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['ok' => true, 'mensaje' => 'Producto ocultado con éxito']);
    } else {
        echo json_encode(['ok' => false, 'mensaje' => 'Error al actualizar el producto']);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(['ok' => false, 'mensaje' => 'Error en la consulta SQL']);
}