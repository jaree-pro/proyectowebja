<?php
$host = "localhost";
$user = "jaredgarcia";
$pass = "Megustaelmango87";
$db   = "proyecto_web";
$port = 3306; 

$conexion = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Para que acepten nombres con acentos o eñes
mysqli_set_charset($conexion, "utf8");
?>