<?php
$servidor = "localhost";
$usuario = "root";
$password = "";  // Vacío en Linux si no configuraste contraseña
$base_datos = "sistema_escolar";

// Conectar a MySQL (sin especificar base de datos)
$conn = new mysqli($servidor, $usuario, $password);

if ($conn->connect_error) {
    die("❌ Error de conexión MySQL: " . $conn->connect_error);
}

// Seleccionar la base de datos (crearla si no existe)
$conn->query("CREATE DATABASE IF NOT EXISTS $base_datos");
$conn->select_db($base_datos);

// echo "✅ Conectado a MySQL";
?>