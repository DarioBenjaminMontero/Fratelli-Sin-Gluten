<?php
require_once __DIR__ . '/../config/config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $sql = "UPDATE productos SET esta_borrado = 1 WHERE ProductoID = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header('Location: ../views/index.php?page=admin&subpage=productos');
exit;