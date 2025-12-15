<?php
$servidor = "localhost";
$usuario = "root";
$password = "TU_CONTRASEÑA"; 
$base_datos = "sistema_escolar"; 

$conn = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>