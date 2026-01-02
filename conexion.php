<?php
// Archivo de conexión a la base de datos - Generado por instalador
$host = "localhost";
$username = "root";
$password = "";
$database = "sistema_escolar";

// Conexión MySQLi
$conn = new mysqli($host, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Configurar charset
$conn->set_charset("utf8mb4");

// Conexión PDO (para algunos módulos)
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error PDO: " . $e->getMessage());
}
?>