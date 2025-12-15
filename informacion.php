<?php
include 'conexion.php';
$mensaje_enviado = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $nombre = $_POST['nombre'];
    $destinatario = $_POST['destinatario'];
    $asunto = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    $sql = "INSERT INTO mensajes (remitente_nombre, destinatario_tipo, asunto, mensaje) 
            VALUES ('$nombre', '$destinatario', '$asunto', '$mensaje')";
    
    if ($conn->query($sql) === TRUE) 
    {
        $mensaje_enviado = true;
    }
    else 
    {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Solicitar Información</title>
</head>
<body>

    <h1>Solicitar Información de Progreso</h1>
    <p>¿Tienes dudas sobre el rendimiento de tu hijo? Envía un mensaje directo a la escuela.</p>

    <?php if ($mensaje_enviado): ?>
        <h3 style="color: green;">Mensaje enviado correctamente! Pronto te responderemos.</h3>
    <?php endif; ?>

    <form action="contacto.php" method="POST" style="border: 1px solid #ccc; padding: 20px; max-width: 500px;">
        
        <label>Tu Nombre (Padre/Tutor):</label><br>
        <input type="text" name="nombre" required style="width: 100%;"><br><br>

        <label>¿A quién va dirigido?</label><br>
        <select name="destinatario" style="width: 100%;">
            <option value="Profesor">Al Profesor de Grupo</option>
            <option value="Administrativo">A Control Escolar (Administración)</option>
            <option value="Director">A Dirección</option>
        </select><br><br>

        <label>Asunto:</label><br>
        <select name="asunto" style="width: 100%;">
            <option value="Progreso Académico">Solicitar reporte de progreso</option>
            <option value="Justificante">Enviar justificante médico</option>
            <option value="Cita">Solicitar cita presencial</option>
        </select><br><br>

        <label>Escribe tu duda o mensaje:</label><br>
        <textarea name="mensaje" rows="5" required style="width: 100%;"></textarea><br><br>

        <button type="submit">Enviar Solicitud</button>
    </form>

    <br>
    <a href="index.php">⬅ Volver al Inicio</a>

</body>
</html>