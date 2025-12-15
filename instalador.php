<?php
include 'conexion.php';

echo "<h2>🛠️ Instalando Base de Datos</h2>";
echo "<div style='font-family: monospace; background: #f5f5f5; padding: 20px;'>";

if (!file_exists('horario.sql')) {
    die("❌ ERROR: No se encuentra horario.sql");
}

// Leer archivo
$sql_content = file_get_contents('horario.sql');
echo "📄 Archivo SQL leído (" . strlen($sql_content) . " bytes)<hr>";

// LIMPIAR el SQL: eliminar comentarios y líneas vacías
$sql_content = preg_replace('/--.*$/m', '', $sql_content); // Eliminar comentarios --
$sql_content = preg_replace('/\/\*.*?\*\//s', '', $sql_content); // Eliminar /* */
$sql_content = preg_replace('/^\s*$/m', '', $sql_content); // Eliminar líneas vacías

// Separar por punto y coma (mejor método)
$queries = [];
$current_query = '';
$lines = explode("\n", $sql_content);

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) continue;
    
    $current_query .= ' ' . $line;
    
    // Si la línea termina con ;, es el final de la consulta
    if (substr($line, -1) == ';') {
        $queries[] = trim($current_query);
        $current_query = '';
    }
}

// Si queda algo al final
if (!empty($current_query)) {
    $queries[] = trim($current_query);
}

$contador = 0;
$errors = 0;

foreach ($queries as $query) {
    $query = trim($query);
    if (empty($query)) continue;
    
    $contador++;
    echo "<strong>Consulta #$contador:</strong><br>";
    echo "<div style='background: white; padding: 5px; margin: 5px 0; border-left: 4px solid #4CAF50;'>";
    echo htmlspecialchars(substr($query, 0, 150)) . (strlen($query) > 150 ? "..." : "") . "<br>";
    
    // Ejecutar consulta
    if ($conn->query($query) === TRUE) {
        echo "<span style='color: green;'>✅ Ejecutada</span>";
    } else {
        echo "<span style='color: red;'>❌ ERROR: " . $conn->error . "</span>";
        $errors++;
    }
    echo "</div>";
}

echo "<hr><strong>Resultado:</strong><br>";
if ($errors == 0) {
    echo "<span style='color: green; font-size: 1.2em;'>✅ INSTALACIÓN EXITOSA</span><br>";
    echo "Todas las $contador consultas se ejecutaron correctamente.<br>";
} else {
    echo "<span style='color: red; font-size: 1.2em;'>⚠️ INSTALACIÓN CON ERRORES</span><br>";
    echo "$errors de $contador consultas fallaron.<br>";
}

// Verificar tablas creadas
echo "<br><strong>📋 Tablas en la base de datos:</strong><br>";
$result = $conn->query("SHOW TABLES");
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "⚠️ No hay tablas<br>";
}

echo "</div>";

echo "<h3>🎉 Proceso completado</h3>";
echo "<div style='margin: 20px 0;'>";
echo "<a href='index.php' style='padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px;'>Ir al Sistema Principal</a> ";
echo "<a href='horario.php' style='padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 5px;'>Ver Horario</a>";
echo "</div>";

echo "<div style='background: #fff3cd; padding: 10px; border: 1px solid #ffeaa7; margin: 20px 0;'>";
echo "<strong>⚠️ IMPORTANTE:</strong> Después de verificar que todo funciona, puedes:<br>";
echo "1. <strong>Eliminar instalar.php</strong> para evitar que otros lo ejecuten<br>";
echo "2. <strong>Mantener horario.sql</strong> como respaldo de la estructura<br>";
echo "3. Usar <strong>conexion_simple.php</strong> en todos tus archivos PHP";
echo "</div>";
?>