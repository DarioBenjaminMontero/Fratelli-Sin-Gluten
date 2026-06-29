<?php
session_start();
define('VIEWS_PATH', __DIR__ . DIRECTORY_SEPARATOR);

$page = $_GET['page'] ?? "main";
switch($page){

case 'main':
    $vista = './main.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
    $css = '../CSS/cssMain.css';
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
}
include(VIEWS_PATH . 'layout.php');
?>