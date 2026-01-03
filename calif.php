<?php
session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: inicias.php');
    exit();
}

require('conexion.php'); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones</title>
    <link rel="stylesheet" href="./css/estilo3.css">
    <style>
        /* Estilos para integrar con tu sistema */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .system-header {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .user-info {
            font-size: 14px;
        }
        
        .system-menu {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .system-menu a {
            background-color: #2196F3;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
            display: inline-block;
        }
        
        .system-menu a:hover {
            background-color: #1976D2;
        }
        
        .logout-btn {
            background-color: #dc3545 !important;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .back-link {
            display: inline-block;
            margin: 20px 0;
            color: #2196F3;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="system-header">
        <div>
            <h2 style="margin: 0;">📚 Sistema Escolar</h2>
            <div class="user-info">
                👤 <?php echo htmlspecialchars($_SESSION['nombre'] ?? 'Usuario'); ?> 
                | 📋 <?php echo ucfirst($_SESSION['tipo'] ?? 'invitado'); ?>
            </div>
        </div>
        <div>
            <a href="logout.php" class="logout-btn" style="background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">🚪 Cerrar Sesión</a>
        </div>
    </div>
    
    <!-- Menú de navegación -->
    <div class="system-menu">
        <a href="home.php">🏠 Inicio</a>
        <a href="horario.php">📅 Horario</a>
        <a href="calif.php" style="background: #4CAF50;">📊 Calificaciones</a>
        <a href="informacion.php">✉️ Contactar Escuela</a>
        <a href="emergencia.php">🚨 Contacto Emergencia</a>
    </div>
    
    <div class="container">
        <!-- Tu contenido actual de calificaciones -->
        <header>
            <h1>📊 Calificaciones del Alumno</h1>
        </header>
        
        <main>
            <section>
                <article>
                    <?php
                    require('tabla.php');
                    ?>
                </article>
            </section>
        </main>
        
        <a href="index.php" class="back-link">⬅ Volver al Inicio</a>
        
        <footer>
            <p><strong>Sistema Escolar © 2024</strong></p>
        </footer>
    </div>
</body>
</html>
