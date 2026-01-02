<?php
// Configuración básica
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sistema_escolar';

// Estilos
echo "<!DOCTYPE html>
<html>
<head>
    <title>Instalador Sistema Escolar</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        h1 { color: #1a2980; }
        h2 { color: #4CAF50; margin-top: 25px; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin: 5px 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .query { background: #f8f9fa; padding: 8px; margin: 3px 0; font-family: monospace; font-size: 11px; border-left: 3px solid #6c757d; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn-blue { background: #2196F3; }
        .btn-purple { background: #9C27B0; }
        .status { padding: 5px; margin: 2px 0; }
        .loading { color: #17a2b8; }
        .done { color: #28a745; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <h1>🚀 Instalador Completo del Sistema Escolar</h1>";

// Función para procesar archivos SQL correctamente
function procesarArchivoSQL($conn, $archivo) {
    echo "<div class='status loading'>📁 Procesando $archivo...</div>";
    
    if (!file_exists($archivo)) {
        echo "<div class='error'>❌ Archivo no encontrado: $archivo</div>";
        return 0;
    }
    
    $contenido = file_get_contents($archivo);
    if ($contenido === false) {
        echo "<div class='error'>❌ No se pudo leer: $archivo</div>";
        return 0;
    }
    
    echo "<div class='success'>✅ Archivo leído (" . strlen($contenido) . " bytes)</div>";
    
    // Limpiar contenido SQL
    $contenido = preg_replace('/\/\*.*?\*\//s', '', $contenido); // Eliminar /* comentarios */
    $contenido = preg_replace('/--.*$/m', '', $contenido); // Eliminar -- comentarios
    $contenido = preg_replace('/^\s*$/m', '', $contenido); // Eliminar líneas vacías
    
    // Separar consultas mejor
    $consultas = array();
    $consulta_actual = '';
    $en_string = false;
    $delimitador = '';
    
    // Recorrer carácter por carácter
    for ($i = 0; $i < strlen($contenido); $i++) {
        $caracter = $contenido[$i];
        $consulta_actual .= $caracter;
        
        // Manejar strings
        if (($caracter == "'" || $caracter == '"') && ($i == 0 || $contenido[$i-1] != "\\")) {
            if (!$en_string) {
                $en_string = true;
                $delimitador = $caracter;
            } elseif ($delimitador == $caracter) {
                $en_string = false;
            }
        }
        
        // Si encontramos punto y coma y no estamos en un string, es fin de consulta
        if ($caracter == ';' && !$en_string) {
            $consulta = trim($consulta_actual);
            if (!empty($consulta)) {
                $consultas[] = $consulta;
            }
            $consulta_actual = '';
        }
    }
    
    // Si queda algo al final
    if (!empty(trim($consulta_actual))) {
        $consultas[] = trim($consulta_actual);
    }
    
    $ejecutadas = 0;
    $errores = 0;
    
    echo "<div class='query'>Encontradas " . count($consultas) . " consultas en $archivo</div>";
    
    // Ejecutar cada consulta
    foreach ($consultas as $index => $sql) {
        $sql = trim($sql);
        if (empty($sql)) continue;
        
        $numero = $index + 1;
        $sql_short = substr($sql, 0, 80);
        if (strlen($sql) > 80) $sql_short .= "...";
        
        echo "<div class='query'>[$archivo #$numero] $sql_short</div>";
        
        // Ejecutar consulta
        if ($conn->query($sql) === TRUE) {
            $ejecutadas++;
            echo "<div class='status done'>✅ Consulta #$numero ejecutada</div>";
        } else {
            $errores++;
            // Mostrar error pero continuar (algunas consultas pueden fallar si las tablas ya existen)
            echo "<div class='warning'>⚠️ Consulta #$numero: " . $conn->error . "</div>";
        }
        
        // Pequeña pausa para no sobrecargar
        usleep(50000); // 50ms
    }
    
    echo "<div class='success'>✅ $archivo: $ejecutadas consultas ejecutadas, $errores errores</div>";
    return $ejecutadas;
}

// 1. Conectar a MySQL
echo "<h2>1. Conectando a MySQL...</h2>";
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("<div class='error'>❌ Error de conexión a MySQL: " . $conn->connect_error . "</div>");
}
echo "<div class='success'>✅ Conexión a MySQL exitosa</div>";

// 2. Crear base de datos
echo "<h2>2. Creando base de datos...</h2>";
if ($conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci")) {
    echo "<div class='success'>✅ Base de datos '$dbname' creada/verificada</div>";
} else {
    die("<div class='error'>❌ Error creando base de datos: " . $conn->error . "</div>");
}

// 3. Seleccionar base de datos
$conn->select_db($dbname);
echo "<div class='success'>✅ Base de datos seleccionada</div>";

// 4. Verificar archivos SQL
echo "<h2>3. Verificando archivos SQL...</h2>";

$archivos_sql = array();
if (file_exists('horario.sql')) {
    $archivos_sql[] = 'horario.sql';
    echo "<div class='success'>✅ Encontrado: horario.sql</div>";
} else {
    echo "<div class='warning'>⚠️ No encontrado: horario.sql</div>";
}

if (file_exists('materias.sql')) {
    $archivos_sql[] = 'materias.sql';
    echo "<div class='success'>✅ Encontrado: materias.sql</div>";
} else {
    echo "<div class='warning'>⚠️ No encontrado: materias.sql</div>";
}

// 5. Procesar archivos SQL
echo "<h2>4. Procesando archivos SQL...</h2>";

$total_consultas = 0;
foreach ($archivos_sql as $archivo) {
    $total_consultas += procesarArchivoSQL($conn, $archivo);
    echo "<hr>";
}

// 6. Verificar y completar tablas faltantes
echo "<h2>5. Verificando estructura completa...</h2>";

// Lista de tablas necesarias
$tablas_necesarias = array(
    'usuarios' => "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        tipo ENUM('alumno','profesor','admin','padre') DEFAULT 'alumno',
        nivel VARCHAR(50),
        grupo VARCHAR(50),
        boleta VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci",
    
    // Si no se creó materias desde el SQL, crearla
    'materias_extra' => "CREATE TABLE IF NOT EXISTS materias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nivel ENUM('Kinder','Primaria','Secundaria') NOT NULL,
        semestre INT NOT NULL,
        clave VARCHAR(10) NOT NULL,
        materia VARCHAR(150) NOT NULL,
        calificacion INT DEFAULT NULL,
        periodo VARCHAR(10) DEFAULT NULL,
        forma_evaluacion VARCHAR(10) DEFAULT NULL,
        estado VARCHAR(20) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci"
);

foreach ($tablas_necesarias as $nombre => $sql) {
    // Verificar si la tabla existe
    $result = $conn->query("SHOW TABLES LIKE '$nombre'");
    if ($result->num_rows == 0) {
        echo "<div class='warning'>⚠️ Tabla '$nombre' no existe, creando...</div>";
        if ($conn->query($sql)) {
            echo "<div class='success'>✅ Tabla '$nombre' creada</div>";
        } else {
            echo "<div class='error'>❌ Error creando '$nombre': " . $conn->error . "</div>";
        }
    }
}

// 7. Crear usuario admin si no existe
echo "<h2>6. Configurando usuarios...</h2>";

// Verificar si hay usuarios
$result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
$count = $result ? $result->fetch_assoc()['total'] : 0;

if ($count == 0) {
    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre, email, password, tipo) VALUES 
            ('Administrador', 'admin@escuela.com', '$password_hash', 'admin')";
    
    if ($conn->query($sql)) {
        echo "<div class='success'>✅ Usuario administrador creado</div>";
    } else {
        echo "<div class='error'>❌ Error creando usuario: " . $conn->error . "</div>";
    }
} else {
    echo "<div class='success'>✅ Ya existen $count usuarios en el sistema</div>";
}

// 8. Crear archivo conexion.php
echo "<h2>7. Creando archivo de conexión...</h2>";

$conexion_content = '<?php
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
?>';

if (file_put_contents('conexion.php', $conexion_content)) {
    echo "<div class='success'>✅ Archivo 'conexion.php' creado</div>";
} else {
    echo "<div class='warning'>⚠️ No se pudo crear conexion.php, créalo manualmente:</div>";
    echo "<div class='query'>" . htmlspecialchars($conexion_content) . "</div>";
}

// 9. Mostrar resumen final
echo "<h2>8. Resumen de la instalación</h2>";

$result = $conn->query("SHOW TABLES");
echo "<div class='info'>";
echo "<h3>📊 Tablas en la base de datos:</h3>";
echo "<ul>";

$tablas = array();
while ($row = $result->fetch_array()) {
    $tabla = $row[0];
    $tablas[] = $tabla;
    
    // Contar registros
    $count_result = $conn->query("SELECT COUNT(*) as total FROM `$tabla`");
    $count = $count_result ? $count_result->fetch_assoc()['total'] : 0;
    
    echo "<li><strong>$tabla</strong> - $count registros</li>";
}
echo "</ul>";
echo "</div>";

echo "<div class='success' style='font-size: 1.2em; padding: 15px;'>";
echo "🎉 <strong>¡INSTALACIÓN COMPLETADA!</strong><br>";
echo "Se procesaron " . count($archivos_sql) . " archivos SQL con $total_consultas consultas";
echo "</div>";

// 10. Mostrar enlaces y credenciales
echo "<div style='text-align: center; margin: 30px 0; padding: 20px; background: #e8f4fd; border-radius: 10px;'>";
echo "<h3>🔑 Acceso al sistema:</h3>";
echo "<p><strong>Usuario administrador:</strong> admin@escuela.com</p>";
echo "<p><strong>Contraseña:</strong> admin123</p>";
echo "<br>";
echo "<a href='inicias.php' class='btn'>Iniciar Sesión</a>";
echo "<a href='index.php' class='btn btn-blue'>Página Principal</a>";
echo "<a href='calif.php' class='btn btn-purple'>Ver Calificaciones</a>";
echo "</div>";

// 11. Advertencias de seguridad
echo "<div class='warning'>";
echo "<h3>⚠️ IMPORTANTE - Medidas de seguridad:</h3>";
echo "<ol>";
echo "<li><strong>Elimina este instalador</strong> (instalador.php) para evitar que otros lo usen</li>";
echo "<li><strong>Cambia la contraseña del administrador</strong> inmediatamente</li>";
echo "<li><strong>Revisa que todas las funciones del sistema</strong> trabajen correctamente</li>";
echo "<li><strong>Haz una copia de seguridad</strong> de la base de datos</li>";
echo "</ol>";
echo "</div>";

// Cerrar conexión
$conn->close();

echo "        </div>
    </div>
</body>
</html>";
?>