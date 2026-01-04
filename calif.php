<?php
session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: inicias.php');
    exit();
}

$boleta = $_SESSION['boleta'];
$nombre = $_SESSION['nombre'];
$nivel_alumno = $_SESSION['nivel'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones</title>
    <link rel="stylesheet" href="./css/estilo3.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; }
        .system-header { background: linear-gradient(135deg, #1a2980, #26d0ce); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .system-menu { background: white; padding: 15px 30px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .system-menu a { background-color: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        .nivel { background: #e3f2fd; padding: 10px; border-left: 5px solid #2196F3; margin-top: 20px; }
        .promedio-general { background: #e8f5e9; padding: 10px; margin-bottom: 15px; font-weight: bold; text-align: right; color: #2e7d32; }
        .semestre { margin-top: 15px; border: 1px solid #ddd; background: white; padding: 15px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4CAF50; color: white; padding: 8px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>

    <div class="system-header">
        <div>
            <h2 style="margin: 0;">📚 Sistema Escolar</h2>
            <small>👤 <?php echo $nombre; ?> | Boleta: <?php echo $boleta; ?></small>
        </div>
        <a href="logout.php" style="background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">Cerrar Sesión</a>
    </div>

    <div class="system-menu">
        <a href="index.php">🏠 Inicio</a>
        <a href="horario.php">📅 Horario</a>
        <a href="calif.php" style="background: #4CAF50;">📊 Calificaciones</a>
        <a href="informacion.php">✉️ Contactar Escuela</a>
        <a href="contacto.php">🚨 Contacto Emergencia</a>
    </div>

    <div class="container">
        <h1>Historial Académico</h1>
        
        <?php 
        require('tabla.php'); 
        ?>
        
        <br>
        <a href="index.php" style="color: #2196F3; text-decoration: none;">⬅ Volver al Inicio</a>
    </div>

</body>
</html>