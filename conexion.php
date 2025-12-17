<?php
$servidor = "localhost";
$usuario = "root";
$password = "";  
$base_datos = "sistema_escolar";

// Conectar a MySQL 
$conn = new mysqli($servidor, $usuario, $password);

if ($conn->connect_error) {
    die("❌ Error de conexión MySQL: " . $conn->connect_error);
}

// Selecciona la base de datos 
$conn->query("CREATE DATABASE IF NOT EXISTS $base_datos");
$conn->select_db($base_datos);

// echo "✅ Conectado a MySQL";
?>