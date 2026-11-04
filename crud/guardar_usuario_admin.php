<?php
session_start();
require_once __DIR__ . '/../config/config.php';

// 1. Verificar que sea una petición POST y que el usuario logueado tenga rol de administrador (1)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['Rol']) || $_SESSION['Rol'] != 1) {
    header('Location: ../views/index.php?page=admin');
    exit;
}

// 2. Recoger y limpiar los datos del formulario
$nombre = trim($_POST['nombre_usuario'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');
$admin = (int)($_POST['admin'] ?? 0); // 0 para cliente, 1 para admin

// 3. Validar que los campos obligatorios no estén vacíos
if (empty($nombre) || empty($correo) || empty($contrasena)) {
    echo "<script>alert('Por favor, completa todos los campos obligatorios.'); window.history.back();</script>";
    exit;
}

// 4. Insertar el nuevo usuario en la base de datos de forma segura mediante sentencia preparada
$sql = "INSERT INTO usuarios (nombre_usuario, correo, contraseña, admin) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "sssi", $nombre, $correo, $contrasena, $admin);

if (mysqli_stmt_execute($stmt)) {
    // Redirigir de vuelta a la sección de usuarios en el panel de administración
    header('Location: ../views/index.php?page=admin&subpage=usuarios');
    exit;
} else {
    echo "<h3 style='color:red;'>Error al registrar el usuario</h3>";
    echo "<p>" . mysqli_error($conexion) . "</p>";
    echo "<a href='../views/index.php?page=admin&subpage=usuarios'>Volver al panel</a>";
}
?>