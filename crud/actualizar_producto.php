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
$categoria = (int)($_POST['categoria'] ?? 0);       
$subCategoria = (int)($_POST['subCategoria'] ?? 0); 
$descripcion = trim($_POST['descripcion'] ?? '');
$alergenos = trim($_POST['alergenos'] ?? '');       
$imagen = trim($_POST['imagen'] ?? '');

if ($id <= 0 || empty($nombre)) {
    echo "<script>alert('ID o nombre inválido.'); window.history.back();</script>";
    exit;
}

$sql = "UPDATE productos SET nombre_producto = ?, stock_producto = ?, precio = ?, categoria = ?, subCategoria = ?, imagen = ?, descripcion = ?, alergenos = ? WHERE ProductoID = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "sidiiissi", $nombre, $stock, $precio, $categoria, $subCategoria, $imagen, $descripcion, $alergenos, $id);

if (mysqli_stmt_execute($stmt)) {
    header('Location: ../views/index.php?page=admin&subpage=productos');
    exit;
} else {
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>