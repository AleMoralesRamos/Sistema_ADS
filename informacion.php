<?php
session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: inicias.php');
    exit();
}

include 'conexion.php';

$nombre_usuario = $_SESSION['nombre'];
$boleta = $_SESSION['boleta'];

$mensaje_enviado = false;
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $nombre = trim($_POST['nombre']);
    $destinatario = trim($_POST['destinatario']);
    $asunto = trim($_POST['asunto']);
    $mensaje = trim($_POST['mensaje']);

    if (empty($nombre) || empty($destinatario) || empty($asunto) || empty($mensaje)) {
        $error = "Todos los campos son obligatorios";
    } 
    else 
    {
        $nombre = $conn->real_escape_string($nombre);
        $destinatario = $conn->real_escape_string($destinatario);
        $asunto = $conn->real_escape_string($asunto);
        $mensaje = $conn->real_escape_string($mensaje);

        $sql = "INSERT INTO comunicacion (remitente_nombre, destinatario_tipo, asunto, mensaje) 
                VALUES ('$nombre', '$destinatario', '$asunto', '$mensaje')";
        
        if ($conn->query($sql) === TRUE) 
        {
            $mensaje_enviado = true;
        }
        else 
        {
            $error = "Error al enviar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Información</title>
    <link rel="stylesheet" href="./css/estilo3.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; }
        
        .system-header { 
            background: linear-gradient(135deg, #2e7d32, #4CAF50); 
            color: white; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        
        .system-menu { background: white; padding: 15px 30px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .system-menu a { background-color: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block; margin-bottom: 5px; }
        .system-menu a:hover { background-color: #1976D2; }
        
        .container { max-width: 800px; margin: 30px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        
        h1 { color: #2e7d32; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; margin-top: 0; }
        
        label { display: block; margin-top: 15px; font-weight: bold; color: #555; }
        input, select, textarea { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        input:focus, select:focus, textarea:focus { border-color: #4CAF50; outline: none; }
        
        textarea { height: 150px; resize: vertical; }
        
        button { background-color: #4CAF50; color: white; padding: 12px 25px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px; font-size: 16px; width: 100%; font-weight: bold; }
        button:hover { background-color: #45a049; }
        
        .success { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #c3e6cb; text-align: center; }
        .error { background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #f5c6cb; text-align: center; }
    </style>
</head>
<body>

    <div class="system-header">
        <div>
            <h2 style="margin: 0;"> Sistema Escolar</h2>
            <small>👤 <?php echo $nombre_usuario; ?> | Boleta: <?php echo $boleta; ?></small>
        </div>
        <a href="logout.php" style="background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">Cerrar Sesión</a>
    </div>

    <div class="system-menu">
        <a href="index.php"> Inicio</a>
        <a href="horario.php"> Horario</a>
        <a href="calif.php"> Calificaciones</a>
        <a href="informacion.php" style="background: #4CAF50;"> Contactar Escuela</a>
        <a href="contacto.php"> Contacto Emergencia</a>
    </div>

    <div class="container">
        <h1> Solicitar Información</h1>
        <p style="color: #666;">¿Tienes dudas sobre el rendimiento académico? Envía un mensaje directo al área correspondiente.</p>

        <?php if ($mensaje_enviado): ?>
            <div class="success">
                <h3 style="margin: 0;"> ¡Mensaje enviado correctamente!</h3>
                <p>Pronto te responderemos. Tu solicitud ha sido registrada.</p>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="informacion.php" style="color: #4CAF50; text-decoration: none; font-weight: bold;">Enviar otro mensaje</a>
            </div>
        <?php else: ?>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="informacion.php" method="POST">
                <label>Tu Nombre (Padre/Tutor o Alumno):</label>
                <input type="text" name="nombre" required value="<?php echo htmlspecialchars($nombre_usuario); ?>" placeholder="Escribe tu nombre completo">
                
                <label>¿A quién va dirigido?</label>
                <select name="destinatario" required>
                    <option value="">-- Selecciona un destinatario --</option>
                    <option value="Profesor"> Al Profesor de Grupo</option>
                    <option value="Administrativo"> A Control Escolar (Administración)</option>
                    <option value="Director"> A Dirección</option>
                    <option value="Psicologia"> Departamento Psicopedagógico</option>
                </select>
                
                <label>Asunto:</label>
                <select name="asunto" required>
                    <option value="">-- Selecciona un asunto --</option>
                    <option value="Progreso Académico"> Solicitar reporte de progreso</option>
                    <option value="Justificante"> Enviar justificante médico</option>
                    <option value="Cita"> Solicitar cita presencial</option>
                    <option value="Dudas Pagos"> Aclaración de pagos</option>
                    <option value="Otro"> Otro</option>
                </select>
                
                <label>Escribe tu mensaje detallado:</label>
                <textarea name="mensaje" required placeholder="Hola, quisiera solicitar información sobre..."></textarea>
                
                <button type="submit"> Enviar Solicitud</button>
            </form>

        <?php endif; ?>
        
        <br>
        <a href="index.php" style="color: #2196F3; text-decoration: none;">⬅ Volver al Inicio</a>
    </div>

</body>
</html>