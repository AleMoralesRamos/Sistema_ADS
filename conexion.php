<?php
$servidor = "localhost";
$usuario = "root";
$password = "";   
$base_datos = "sistema_escolar"; 

$conn = new mysqli($servidor, $usuario, $password);

if ($conn->connect_error) 
{
    $conn = new mysqli("127.0.0.1", "root", "root", $base_datos);
    
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
}

$sql_create_db = "CREATE DATABASE IF NOT EXISTS $base_datos";
if ($conn->query($sql_create_db) === TRUE) 
{
    $conn->select_db($base_datos);
    
    $sql_tablas = "
    CREATE TABLE IF NOT EXISTS horarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        dia VARCHAR(20) NOT NULL,
        ini TIME NOT NULL,
        fin TIME NOT NULL,
        materia VARCHAR(100) NOT NULL,
        profesor VARCHAR(100)
    );
    
    CREATE TABLE IF NOT EXISTS calendario_eventos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fecha DATE NOT NULL,
        evento VARCHAR(255) NOT NULL,
        tipo VARCHAR(50) 
    );
    
    CREATE TABLE IF NOT EXISTS mensajes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        remitente_nombre VARCHAR(100),
        destinatario_tipo VARCHAR(50),
        asunto VARCHAR(150),
        mensaje TEXT,
        fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP
    );
    ";
    
    $tablas = explode(';', $sql_tablas);
    foreach ($tablas as $tabla) {
        if (trim($tabla) != '') {
            $conn->query($tabla);
        }
    }
    
    $check_horarios = $conn->query("SELECT COUNT(*) as total FROM horarios");
    $row = $check_horarios->fetch_assoc();
    if ($row['total'] == 0) {
        $conn->query("INSERT INTO horarios (dia, ini, fin, materia, profesor) VALUES 
            ('Lunes', '08:00', '09:00', 'Matemáticas', 'Prof. Jirafales'),
            ('Lunes', '09:00', '10:00', 'Historia', 'Prof. X')");
    }
    
    $check_eventos = $conn->query("SELECT COUNT(*) as total FROM calendario_eventos");
    $row2 = $check_eventos->fetch_assoc();
    if ($row2['total'] == 0) {
        $conn->query("INSERT INTO calendario_eventos (fecha, evento, tipo) VALUES 
            (CURDATE(), 'Día del Trabajo', 'Festivo'),
            (DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'Reunión de Padres', 'Académico')");
    }
    
} else {
    die("Error al crear base de datos: " . $conn->error);
}

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>