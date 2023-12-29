<?php
session_start();

$path = $_SERVER["REQUEST_URI"];
switch ($path) {
    case "/":
        require "./views/home.php";
        break;
    case "/register":
        require "./views/register.php";
        break;
    case "/login":
        require "./views/login.php";
        break;
    case "/logout":
        require "./views/logout.php";
        break;
    case "/profile":
        require "./views/profile.php";
        break;
    default:
        require "./views/404.php";
        break;
}
?>