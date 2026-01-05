<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sistema_escolar';  

mysqli_report(MYSQLI_REPORT_OFF);

echo "<!DOCTYPE html>
<html>
<head>
    <title>Instalador Sistema Escolar</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #1a2980; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; border: 1px solid #f5c6cb; }
        .btn { display: inline-block; padding: 10px 20px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='card'>
            <h1>Instalador Simple del Sistema Escolar</h1>";

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("<div class='error'>❌ Error de conexión: " . $conn->connect_error . "</div>");
}

echo "<div class='success'>✅ Conectado a MySQL</div>";

if ($conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    echo "<div class='success'>✅ Base de datos '$dbname' seleccionada</div>";
    $conn->select_db($dbname);
} else {
    die("<div class='error'>❌ Error creando base de datos: " . $conn->error . "</div>");
}

$sql_queries = [
    "DROP TABLE IF EXISTS contactos_emergencia",
    "DROP TABLE IF EXISTS mensajes",
    "DROP TABLE IF EXISTS comunicacion",
    "DROP TABLE IF EXISTS calendario_eventos",
    "DROP TABLE IF EXISTS horarios",
    "DROP TABLE IF EXISTS kardex",
    "DROP TABLE IF EXISTS alumnos", 
    "DROP TABLE IF EXISTS materias",
    "DROP TABLE IF EXISTS usuarios",
    
    // 3. Crear tabla USUARIOS
    "CREATE TABLE usuarios (
        boleta BIGINT(20) NOT NULL PRIMARY KEY,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // 4. Crear tabla ALUMNOS
    "CREATE TABLE alumnos (
        boleta BIGINT(20) NOT NULL PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        apellidos VARCHAR(120) NOT NULL,
        nivel ENUM('Kinder','Primaria','Secundaria','Administrativo') DEFAULT NULL,
        FOREIGN KEY (boleta) REFERENCES usuarios(boleta) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // 5. Crear tabla MATERIAS
    "CREATE TABLE materias (
        clave VARCHAR(10) NOT NULL PRIMARY KEY,
        nivel ENUM('Kinder','Primaria','Secundaria') NOT NULL,
        semestre TINYINT(4) NOT NULL,
        materia VARCHAR(120) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // 6. Crear tabla KARDEX
    "CREATE TABLE kardex (
        boleta BIGINT(20) NOT NULL,
        clave VARCHAR(10) NOT NULL,
        calificacion TINYINT(4) DEFAULT NULL,
        periodo VARCHAR(10) DEFAULT NULL,
        forma_evaluacion ENUM('ORD','REC','EXT') DEFAULT NULL,
        estado ENUM('Aprobada','Reprobada','Sin cursar') DEFAULT 'Sin cursar',
        PRIMARY KEY (boleta, clave),
        FOREIGN KEY (boleta) REFERENCES alumnos(boleta) ON DELETE CASCADE,
        FOREIGN KEY (clave) REFERENCES materias(clave) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // 7. Crear tabla HORARIOS
    "CREATE TABLE horarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nivel ENUM('Kinder','Primaria','Secundaria') NOT NULL,
        dia ENUM('Lunes','Martes','Miércoles','Jueves','Viernes') NOT NULL,
        hora_inicio TIME NOT NULL,
        hora_fin TIME NOT NULL,
        materia VARCHAR(100) NOT NULL,
        profesor VARCHAR(100) DEFAULT 'Por asignar'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // 8. Crear tabla CALENDARIO
    "CREATE TABLE calendario_eventos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fecha DATE NOT NULL,
        evento VARCHAR(150) NOT NULL,
        tipo ENUM('Examen','Suspensión','Evento','Entrega') NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // 9. Crear tabla COMUNICACION
    "CREATE TABLE comunicacion (
        id INT AUTO_INCREMENT PRIMARY KEY,
        remitente_nombre VARCHAR(100) NOT NULL,
        destinatario_tipo VARCHAR(50) NOT NULL,
        asunto VARCHAR(100) NOT NULL,
        mensaje TEXT NOT NULL,
        fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // 10. Crear tabla MENSAJES
    "CREATE TABLE mensajes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        emisor_boleta BIGINT(20) NOT NULL,
        receptor_boleta BIGINT(20) NOT NULL,
        asunto VARCHAR(150) NOT NULL,
        contenido TEXT NOT NULL,
        fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (emisor_boleta) REFERENCES usuarios(boleta) ON DELETE CASCADE,
        FOREIGN KEY (receptor_boleta) REFERENCES usuarios(boleta) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // 11. Crear tabla CONTACTOS DE EMERGENCIA
    "CREATE TABLE contactos_emergencia (
        id INT(11) NOT NULL AUTO_INCREMENT,
        id_usuario BIGINT(20) NOT NULL,
        nombre_completo VARCHAR(100) NOT NULL,
        telefono VARCHAR(20) NOT NULL,
        parentesco VARCHAR(50) NOT NULL,
        fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (id_usuario) REFERENCES usuarios(boleta) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    
    // --- INSERCIONES DE DATOS ---

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
    
    // Insertar usuarios
    "INSERT INTO usuarios (boleta, password, email) VALUES
    (2023630289, 'pepito1', 'juan@correo.com'),
    (2023630290, 'pepito2', 'ana@correo.com'),
    (2023630291, 'pepito3', 'pedro@correo.com'),
    (2023630292, 'pepito4', 'maria@correo.com'),
    (2023630293, 'pepito5', 'carlos@correo.com'),
    (2023630294, 'pepito6', 'laura@correo.com'),
    (2023630295, 'pepito7', 'jorge@correo.com'),
    (2023630296, 'pepito8', 'sofia@correo.com'),
    (2023630297, 'pepito9', 'miguel@correo.com'),
    (2023630298, 'pepito10', 'isabel@correo.com'),
    (9999999999, 'admin123', 'admin@escuela.com')",
    
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
    (2023630298, 'Isabel', 'Torres', 'Kinder'),
    (9999999999, 'DIRECCIÓN', 'ESCOLAR', 'Administrativo')",
    
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
    (2023630290, 'S201', 6, '24/1', 'ORD', 'Aprobada')",

    // Insertar Horarios (CORREGIDOS PARA COINCIDIR CON MATERIAS)
    "INSERT INTO horarios (nivel, dia, hora_inicio, hora_fin, materia, profesor) VALUES
    ('Kinder', 'Lunes', '08:00', '09:00', 'Desarrollo Motriz', 'Prof. Ana'),
    ('Kinder', 'Lunes', '09:00', '10:00', 'Lenguaje Inicial', 'Prof. Luis'),     
    ('Kinder', 'Martes', '08:00', '10:00', 'Expresión Artística', 'Prof. Sol'),
    ('Primaria', 'Lunes', '07:00', '08:30', 'Matemáticas I', 'Prof. Jirafales'),
    ('Primaria', 'Lunes', '08:30', '10:00', 'Español I', 'Prof. Ximena'),         
    ('Primaria', 'Miércoles', '10:30', '12:00', 'Historia', 'Prof. Tenoch'),
    ('Secundaria', 'Lunes', '07:00', '09:00', 'Física', 'Dr. Emmett Brown'),
    ('Secundaria', 'Martes', '07:00', '09:00', 'Química', 'Walter White'),
    ('Secundaria', 'Viernes', '11:00', '13:00', 'Tecnología', 'Prof. Rambo')",

    "INSERT INTO calendario_eventos (fecha, evento, tipo) VALUES
    (CURDATE() + INTERVAL 2 DAY, 'Entrega de Boletas', 'Evento'),
    (CURDATE() + INTERVAL 5 DAY, 'Suspensión de labores', 'Suspensión'),
    (CURDATE() + INTERVAL 10 DAY, 'Examen Parcial Matemáticas', 'Examen'),
    (CURDATE() + INTERVAL 20 DAY, 'Festival de la Primavera', 'Evento')",

    "INSERT INTO mensajes (emisor_boleta, receptor_boleta, asunto, contenido) VALUES 
    (9999999999, 2023630289, 'Aviso Importante', 'Mañana no hay clases.'),
    (9999999999, 2023630289, 'Aviso Importante', 'El dia Viernes junta con padres.')"
];

$errors = [];
$success_count = 0;

foreach ($sql_queries as $index => $sql) {
    if ($conn->query($sql) === TRUE) {
        $success_count++;
    } else {
        $errors[] = "Consulta #" . ($index + 1) . ": " . $conn->error;
    }
}

echo "<div class='success'>✅ Ejecutadas $success_count consultas de " . count($sql_queries) . "</div>";

if (!empty($errors)) {
    echo "<div class='error'>⚠️ Errores encontrados (" . count($errors) . "):</div>";
    foreach ($errors as $error) {
        echo "<div style='color: #856404; padding: 5px; margin: 2px; background: #fff3cd;'>$error</div>";
    }
}

$conexion_content = '<?php
// Conexión a sistema_escolar
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
        if ($usuario["password"] === $password_input) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
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

$conn->close();

echo "<h2>🎉 ¡Instalación completada!</h2>";
echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='inicias.php' class='btn'>Iniciar Sesión</a>";
echo "</div>";

echo "        </div>
    </div>
</body>
</html>";
?>