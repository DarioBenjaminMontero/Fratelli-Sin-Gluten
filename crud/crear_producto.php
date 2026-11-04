<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['Rol']) || $_SESSION['Rol'] != 1) {
    header('Location: ../views/index.php?page=admin');
    exit;
}

$id = (int)($_POST['ProductoID'] ?? 0);
$nombre = trim($_POST['nombre_producto'] ?? '');
$precio = (float)($_POST['precio'] ?? 0);
$stock = (int)($_POST['stock_producto'] ?? 0);
$categoriaID = (int)($_POST['CategoriaID'] ?? 0);
$subcategoriaID = (int)($_POST['SubcategoriaID'] ?? 0);
$descripcion = trim($_POST['descripcion'] ?? '');
$ingredientes = trim($_POST['ingredientes'] ?? '');
$imagen = trim($_POST['imagen'] ?? '');

if ($id <= 0 || empty($nombre)) {
    echo "<script>alert('Datos inválidos.'); window.history.back();</script>";
    exit;
}

$sql = "UPDATE productos SET nombre_producto = ?, precio = ?, stock_producto = ?, CategoriaID = ?, SubcategoriaID = ?, descripcion = ?, ingredientes = ?, imagen = ? WHERE ProductoID = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "sdiiisssi", $nombre, $precio, $stock, $categoriaID, $subcategoriaID, $descripcion, $ingredientes, $imagen, $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: ../views/index.php?page=admin&subpage=productos');
    exit;
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>