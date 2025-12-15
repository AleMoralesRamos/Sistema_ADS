<?php
include 'conexion.php';

echo "<h3>Instalando base de datos...</h3>";

// Leer y ejecutar horario.sql
$sql = file_get_contents('horario.sql');
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if ($conn->query($query) === TRUE) {
            echo "✅ Ejecutado: " . substr($query, 0, 50) . "...<br>";
        } else {
            echo "❌ Error: " . $conn->error . "<br>";
        }
    }
}

echo "<h3>✅ Instalación completada</h3>";
echo "<a href='index.php'>Ir al sistema</a>";
?>