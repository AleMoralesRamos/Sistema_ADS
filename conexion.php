<?php
$conn = new mysqli("localhost", "root", "tu_contraseña", "nombre_de_tu_base_datos");
if ($conn->connect_error) { die("Fallo conexion: " . $conn->connect_error); }
?>