<?php
$host = "localhost"; $username = "root"; $password = ""; $database = "sistema_escolar"; 
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) { die("Error: " . $conn->connect_error); }
$conn->set_charset("utf8mb4");

function verificarUsuario($boleta, $password_input) {
    global $conn;
    $sql = "SELECT u.boleta, u.password, a.nombre, a.apellidos, a.nivel, g.nombre as nombre_grupo 
            FROM usuarios u 
            LEFT JOIN alumnos a ON u.boleta = a.boleta 
            LEFT JOIN alumno_grupo ag ON a.boleta = ag.boleta
            LEFT JOIN grupos g ON ag.grupo_id = g.id
            WHERE u.boleta = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $boleta);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();
        if ($usuario["password"] === $password_input) {
            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            $_SESSION["boleta"] = $usuario["boleta"];
            $_SESSION["nombre"] = $usuario["nombre"] . " " . $usuario["apellidos"];
            $_SESSION["nivel"] = $usuario["nivel"];
            // Guardamos el grupo en sesion
            $_SESSION["grado_grupo"] = $usuario["nombre_grupo"] ?? "Sin Asignar";
            $_SESSION["autenticado"] = true;
            return true;
        }
    }
    return false;
}
?>