<?php
define('VIEWS_PATH', __DIR__ . DIRECTORY_SEPARATOR);

$page = $_GET['page'] ?? "main";
switch($page){

case 'main':
    $vista = './main.php';
    $vistaHeader = './header.php';
    $head = './head.php';
    $footer = './footer.php';
break;
}
include(VIEWS_PATH . 'layout.php');
?>