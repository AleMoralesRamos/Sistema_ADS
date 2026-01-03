<?php
// Conexión a proyectoe2 - CONTRASEÑAS NORMALES
$host = "localhost";
$username = "root";
$password = "";
$database = "sistema_escolar"; 

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

function verificarUsuario($boleta, $password_input) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT u.boleta, u.password, a.nombre, a.apellidos, a.nivel 
                            FROM usuarios u 
                            LEFT JOIN alumnos a ON u.boleta = a.boleta 
                            WHERE u.boleta = ?");
    $stmt->bind_param("s", $boleta);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        // COMPARACIÓN DIRECTA - CONTRASEÑAS NORMALES
        if ($usuario["password"] === $password_input) {
            session_start();
            $_SESSION["boleta"] = $usuario["boleta"];
            $_SESSION["nombre"] = $usuario["nombre"] . " " . $usuario["apellidos"];
            $_SESSION["nivel"] = $usuario["nivel"];
            $_SESSION["autenticado"] = true;
            return true;
        }
    }
    return false;
}
?>