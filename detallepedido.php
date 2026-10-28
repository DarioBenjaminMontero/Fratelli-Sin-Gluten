<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config/config.php';
if (!$conexion) {
    echo json_encode(["error" => "No se pudo conectar a la base de datos"]);
    exit;
}
else if(!empty($_POST["numero"])){
    $query = "SELECT detallepedido.precio,detallepedido.cantidad,productos.nombre_producto,productos.imagen FROM detallepedido inner join productos on detallepedido.ProductoID=productos.ProductoID WHERE PedidoID = ?;";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "i", $_POST["numero"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            $pedidos = [];
            while($row = mysqli_fetch_assoc($result)) {

                $pedidos[] = $row;
            }
            echo json_encode($pedidos);
        } else {
            echo json_encode(["success" => true, "pedidos" => ""]);
        }
}
else {
    echo json_encode(["error" => "Faltan datos requeridos"]);
    mysqli_close($conexion);
}