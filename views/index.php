<?php
session_start();
define('VIEWS_PATH', __DIR__ . DIRECTORY_SEPARATOR);
$_SESSION['user_id']=1; 
$_SESSION['nombre_usuario']="santi"; 
$_SESSION['ubicacion'] = "";         
$_SESSION['casa_departamento'] = "";        
$_SESSION['numero'] = "";         
$_SESSION['piso'] = "";

$page = $_GET['page'] ?? "main";
switch($page){

case 'main':
    $vista = './main.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssMain.css';
break;
case 'creadores':
    $vista = './sobre-nosotros.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssMain.css';
break;
case 'producto':
    $vista = './producto.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssProducto.css';
break;
case 'categoria':
    $vista = './productos.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssCategoria.css';
 $script = '../JS/cargarProductosPorCategoria.js'; 
break;
case 'Login':
    $vista = './Login.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssLogin.css';
    break;
    case 'Registro':
    $vista = './Register.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssRegister.css';
    break;
    case 'Perfil':
    $vista = './Perfil.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssPerfil.css';
    break;
}
include(VIEWS_PATH . 'layout.php');
?>
