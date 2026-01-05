<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: inicias.php');
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema Escolar - Inicio</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background-color: #4CAF50; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .menu { display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
        .menu a { background-color: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .menu a:hover { background-color: #1976D2; }
        .card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stats { display: flex; gap: 20px; flex-wrap: wrap; }
        .stat-box { flex: 1; min-width: 200px; text-align: center; padding: 20px; background: #e3f2fd; border-radius: 5px; }
        
        .welcome-section { margin-top: 10px; }
        .welcome-message { font-size: 1.2em; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1> Sistema de Gestión Escolar</h1>
            <p>Bienvenido al sistema de seguimiento académico</p>
            <div class="welcome-section">
                <div class="welcome-message">
                     ¡Hola padre de
                    <?php 
                    if (isset($_SESSION['nombre'])) {
                        echo htmlspecialchars($_SESSION['nombre']);
                    } else {
                        echo "Alumno";
                    }
                    ?>!
                </div>
            </div> </header>
        
        <div class="menu">
            <a href="index.php"> Inicio</a>
            <a href="horario.php"> Horario</a>
            <a href="calif.php"> Calificaciones</a>
            <a href="informacion.php"> Contactar Escuela</a>
            <a href="contacto.php"> Contactos Emergencia</a>
            <a href="mensaje_situaciones.php"> Incidencias</a>
            <a href="logout.php" style="background-color: #dc3545;"> Cerrar Sesión</a>
        </div>
        
        <div class="card">
            <h2>Resumen del Sistema</h2>
            <div class="stats">
                <div class="stat-box">
                    <h3></h3>
                    <h4>Horario Semanal</h4>
                    <p>Consulta las clases de la semana</p>
                    <a href="horario.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">Ver Horario</a>
                </div>
                <div class="stat-box">
                    <h3></h3>
                    <h4>Eventos</h4>
                    <p>Próximas actividades escolares</p>
                    <a href="horario.php#eventos" style="color: #2196F3; text-decoration: none; font-weight: bold;">Ver Eventos</a>
                </div>
                <div class="stat-box">
                    <h3></h3>
                    <h4>Comunicación</h4>
                    <p>Contacta con la escuela</p>
                    <a href="informacion.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">Enviar Mensaje</a>
                </div>
                <div class="stat-box">
                    <h3></h3>
                    <h4>Emergencia</h4>
                    <p>Agrega un contacto</p>
                    <a href="contacto.php" style="color: #2196F3; text-decoration: none; font-weight: bold;">Gestionar</a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Accesos Rápidos</h2>
            <ul style="list-style-type: none; padding: 0;">
                <li style="margin: 10px 0;"><a href="horario.php" style="color: #333; text-decoration: none;">▶ Ver horario completo</a></li>
                <li style="margin: 10px 0;"><a href="informacion.php" style="color: #333; text-decoration: none;">▶ Solicitar información académica</a></li>
                <li style="margin: 10px 0;"><a href="informacion.php" style="color: #333; text-decoration: none;">▶ Enviar justificante</a></li>
                <li style="margin: 10px 0;"><a href="informacion.php" style="color: #333; text-decoration: none;">▶ Solicitar cita</a></li>
            </ul>
        </div>
    </div>
</body>
</html>