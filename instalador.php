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
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #1a2980; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <h1>Instalador Simple del Sistema Escolar</h1>";

// Conectar a MySQL
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("<div class='error'>❌ Error de conexión: " . $conn->connect_error . "</div>");
}

echo "<div class='success'>✅ Conectado a MySQL</div>";

// Crear base de datos
if ($conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    echo "<div class='success'>✅ Base de datos '$dbname' creada</div>";
    $conn->select_db($dbname);
} else {
    die("<div class='error'>❌ Error creando base de datos</div>");
}

// SQL embebido directamente en el código
$sql_queries = [
    "DROP TABLE IF EXISTS kardex",
    "DROP TABLE IF EXISTS alumnos",
    "DROP TABLE IF EXISTS materias",
    "DROP TABLE IF EXISTS usuarios",
    
    // Crear tabla usuarios
    "CREATE TABLE usuarios (
        boleta BIGINT(20) NOT NULL PRIMARY KEY,
        password VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // Crear tabla alumnos
    "CREATE TABLE alumnos (
        boleta BIGINT(20) NOT NULL PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        apellidos VARCHAR(120) NOT NULL,
        nivel ENUM('Kinder','Primaria','Secundaria') DEFAULT NULL,
        FOREIGN KEY (boleta) REFERENCES usuarios(boleta)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // Crear tabla materias
    "CREATE TABLE materias (
        clave VARCHAR(10) NOT NULL PRIMARY KEY,
        nivel ENUM('Kinder','Primaria','Secundaria') NOT NULL,
        semestre TINYINT(4) NOT NULL,
        materia VARCHAR(120) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // Crear tabla kardex
    "CREATE TABLE kardex (
        boleta BIGINT(20) NOT NULL,
        clave VARCHAR(10) NOT NULL,
        calificacion TINYINT(4) DEFAULT NULL,
        periodo VARCHAR(10) DEFAULT NULL,
        forma_evaluacion ENUM('ORD','REC','EXT') DEFAULT NULL,
        estado ENUM('Aprobada','Reprobada','Sin cursar') DEFAULT 'Sin cursar',
        PRIMARY KEY (boleta, clave),
        FOREIGN KEY (boleta) REFERENCES alumnos(boleta),
        FOREIGN KEY (clave) REFERENCES materias(clave)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // Insertar materias
    "INSERT INTO materias (clave, nivel, semestre, materia) VALUES
    ('K001', 'Kinder', 1, 'Desarrollo Motriz'),
    ('K002', 'Kinder', 1, 'Lenguaje Inicial'),
    ('K003', 'Kinder', 1, 'Expresión Artística'),
    ('K004', 'Kinder', 2, 'Pensamiento Matemático'),
    ('K005', 'Kinder', 2, 'Convivencia Social'),
    ('P101', 'Primaria', 1, 'Español I'),
    ('P102', 'Primaria', 1, 'Matemáticas I'),
    ('P103', 'Primaria', 2, 'Ciencias Naturales'),
    ('P104', 'Primaria', 2, 'Historia'),
    ('P105', 'Primaria', 3, 'Geografía'),
    ('P106', 'Primaria', 3, 'Formación Cívica y Ética'),
    ('S201', 'Secundaria', 1, 'Matemáticas Avanzadas'),
    ('S202', 'Secundaria', 1, 'Física'),
    ('S203', 'Secundaria', 2, 'Química'),
    ('S204', 'Secundaria', 2, 'Tecnología'),
    ('S205', 'Secundaria', 3, 'Formación Cívica')",
    
    // Insertar usuarios TODOS CON CONTRASEÑAS NORMALES (texto plano)
    "INSERT INTO usuarios (boleta, password) VALUES
    (2023630289, 'pepito1'),
    (2023630290, 'pepito2'),
    (2023630291, 'pepito3'),
    (2023630292, 'pepito4'),
    (2023630293, 'pepito5'),
    (2023630294, 'pepito6'),
    (2023630295, 'pepito7'),
    (2023630296, 'pepito8'),
    (2023630297, 'pepito9'),
    (2023630298, 'pepito10')",
    
    // Insertar alumnos
    "INSERT INTO alumnos (boleta, nombre, apellidos, nivel) VALUES
    (2023630289, 'Juan', 'Pérez', 'Kinder'),
    (2023630290, 'Ana', 'López', 'Primaria'),
    (2023630291, 'Pedro', 'García', 'Secundaria'),
    (2023630292, 'María', 'Rodríguez', 'Kinder'),
    (2023630293, 'Carlos', 'Martínez', 'Primaria'),
    (2023630294, 'Laura', 'Hernández', 'Secundaria'),
    (2023630295, 'Jorge', 'González', 'Kinder'),
    (2023630296, 'Sofía', 'Sánchez', 'Primaria'),
    (2023630297, 'Miguel', 'Ramírez', 'Secundaria'),
    (2023630298, 'Isabel', 'Torres', 'Kinder')",
    
    // Insertar kardex
    "INSERT INTO kardex (boleta, clave, calificacion, periodo, forma_evaluacion, estado) VALUES
    (2023630289, 'K001', 9, '24/1', 'ORD', 'Aprobada'),
    (2023630289, 'K002', 10, '24/1', 'ORD', 'Aprobada'),
    (2023630289, 'K004', 8, '24/1', 'ORD', 'Aprobada'),
    (2023630289, 'P101', 7, '24/1', 'ORD', 'Aprobada'),
    (2023630289, 'P102', 6, '24/1', 'ORD', 'Aprobada'),
    (2023630289, 'S202', 5, '24/1', 'ORD', 'Reprobada'),
    (2023630290, 'K001', 10, '24/1', 'ORD', 'Aprobada'),
    (2023630290, 'K003', 9, '24/1', 'ORD', 'Aprobada'),
    (2023630290, 'P103', 8, '24/1', 'ORD', 'Aprobada'),
    (2023630290, 'P104', 7, '24/1', 'ORD', 'Aprobada'),
    (2023630290, 'S201', 6, '24/1', 'ORD', 'Aprobada')"
];

// Ejecutar consultas una por una
$errors = [];
$success_count = 0;

foreach ($sql_queries as $index => $sql) {
    if ($conn->query($sql) === TRUE) {
        $success_count++;
    } else {
        $errors[] = "Consulta #" . ($index + 1) . ": " . $conn->error;
    }
}

// Mostrar resultados
echo "<div class='success'>✅ Ejecutadas $success_count consultas de " . count($sql_queries) . "</div>";

if (!empty($errors)) {
    echo "<div class='error'>⚠️ Errores encontrados (" . count($errors) . "):</div>";
    foreach ($errors as $error) {
        echo "<div style='color: #856404; padding: 5px; margin: 2px; background: #fff3cd;'>$error</div>";
    }
}

// Crear archivo de conexión CORREGIDO
$conexion_content = '<?php
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
?>';

if (file_put_contents('conexion.php', $conexion_content)) {
    echo "<div class='success'>✅ Archivo conexion.php creado</div>";
} else {
    echo "<div class='error'>❌ Error creando conexion.php</div>";
}

// Verificar tablas creadas
echo "<h3>📊 Tablas creadas:</h3>";
$tables = ['usuarios', 'alumnos', 'materias', 'kardex'];
foreach ($tables as $table) {
    $result = $conn->query("SELECT COUNT(*) as count FROM $table");
    if ($result) {
        $count = $result->fetch_assoc()['count'];
        echo "<div>• Tabla <strong>$table</strong>: $count registros</div>";
    } else {
        echo "<div class='error'>❌ Error accediendo a tabla $table</div>";
    }
}

// Mostrar usuarios creados para referencia
echo "<h3>👥 Usuarios creados:</h3>";
$result = $conn->query("SELECT u.boleta, u.password, a.nombre, a.apellidos, a.nivel 
                       FROM usuarios u 
                       LEFT JOIN alumnos a ON u.boleta = a.boleta 
                       ORDER BY u.boleta LIMIT 5");

echo "<table border='1' cellpadding='5' style='margin: 10px 0;'>
        <tr>
            <th>Boleta</th>
            <th>Contraseña</th>
            <th>Nombre</th>
            <th>Nivel</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . $row['boleta'] . "</td>
            <td><strong>" . $row['password'] . "</strong></td>
            <td>" . $row['nombre'] . " " . $row['apellidos'] . "</td>
            <td>" . $row['nivel'] . "</td>
          </tr>";
}
echo "</table>";

$conn->close();

echo "<h2>🎉 ¡Instalación completada!</h2>";
echo "<div style='padding: 20px; background: #e8f4fd; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>🔑 Credenciales de prueba:</h3>";
echo "<p><strong>Usuario 1:</strong> Boleta: <strong>2023630289</strong> | Contraseña: <strong>pepito1</strong></p>";
echo "<p><strong>Usuario 2:</strong> Boleta: <strong>2023630290</strong> | Contraseña: <strong>pepito2</strong></p>";
echo "<p><em>Todas las contraseñas son texto plano: pepito1, pepito2, pepito3, etc.</em></p>";
echo "</div>";

echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='inicias.php' class='btn'>Iniciar Sesión</a>";
echo "<a href='index.php' class='btn' style='background: #2196F3;'>Página Principal</a>";
echo "</div>";

echo "<div class='error'>";
echo "<h3>⚠️ IMPORTANTE:</h3>";
echo "<p>1. <strong>Elimina este instalador</strong> después de usarlo (renómbralo a .bak)</p>";
echo "<p>2. <strong>Cambia las contraseñas</strong> en producción por seguridad</p>";
echo "<p>3. <strong>Base de datos:</strong> proyectoe2</p>";
echo "</div>";

echo "        </div>
    </div>
</body>
</html>";