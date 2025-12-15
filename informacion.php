<?php
include 'conexion.php';
$mensaje_enviado = false;

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

        $sql = "INSERT INTO mensajes (remitente_nombre, destinatario_tipo, asunto, mensaje) 
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
<html>
<head>
    <title>Solicitar Información</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        label { display: block; margin-top: 15px; font-weight: bold; color: #555; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        textarea { height: 150px; resize: vertical; }
        button { background-color: #4CAF50; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px; font-size: 16px; }
        button:hover { background-color: #45a049; }
        .success { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .error { background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .nav { margin: 20px 0; }
        .nav a { color: #2196F3; text-decoration: none; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✉️ Solicitar Información de Progreso</h1>
        <p>¿Tienes dudas sobre el rendimiento de tu hijo? Envía un mensaje directo a la escuela.</p>
        
        <div class="nav">
            <a href="index.php">🏠 Inicio</a> | 
            <a href="horario.php">📅 Horario</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($mensaje_enviado): ?>
            <div class="success">
                <h3>✅ ¡Mensaje enviado correctamente!</h3>
                <p>Pronto te responderemos. Tu mensaje ha sido registrado en nuestro sistema.</p>
            </div>
        <?php endif; ?>

        <form action="informacion.php" method="POST">
            <label>Tu Nombre (Padre/Tutor):</label>
            <input type="text" name="nombre" required placeholder="Ej: Juan Pérez García">
            
            <label>¿A quién va dirigido?</label>
            <select name="destinatario" required>
                <option value="">-- Selecciona un destinatario --</option>
                <option value="Profesor">👨‍🏫 Al Profesor de Grupo</option>
                <option value="Administrativo">📋 A Control Escolar (Administración)</option>
                <option value="Director">👨‍💼 A Dirección</option>
            </select>
            
            <label>Asunto:</label>
            <select name="asunto" required>
                <option value="">-- Selecciona un asunto --</option>
                <option value="Progreso Académico">📊 Solicitar reporte de progreso</option>
                <option value="Justificante">🏥 Enviar justificante médico</option>
                <option value="Cita">🤝 Solicitar cita presencial</option>
                <option value="Otro">❓ Otro</option>
            </select>
            
            <label>Escribe tu duda o mensaje:</label>
            <textarea name="mensaje" required placeholder="Describe tu consulta aquí..."></textarea>
            
            <button type="submit">📤 Enviar Solicitud</button>
        </form>
        
        <div class="nav" style="margin-top: 30px;">
            <a href="index.php">⬅ Volver al Inicio</a>
        </div>
    </div>
</body>
</html>