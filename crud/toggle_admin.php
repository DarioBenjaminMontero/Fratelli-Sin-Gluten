<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$idUsuario = $_GET['id'] ?? null;
$accion = $_GET['action'] ?? null;

if ($idUsuario !== null && $accion !== null) {
    $sql = "UPDATE usuarios SET admin = ? WHERE UsuarioID = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $accion, $idUsuario);
    mysqli_stmt_execute($stmt);
}

header('Location: ../views/index.php?page=admin&subpage=usuarios');
exit;