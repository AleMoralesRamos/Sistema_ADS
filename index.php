<?php
include 'conexion.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistema Escolar - Inicio</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; }
        header { background-color: #4CAF50; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .menu { display: flex; gap: 10px; margin-bottom: 30px; }
        .menu a { background-color: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .menu a:hover { background-color: #1976D2; }
        .card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stats { display: flex; gap: 20px; }
        .stat-box { flex: 1; text-align: center; padding: 20px; background: #e3f2fd; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📚 Sistema de Gestión Escolar</h1>
            <p>Bienvenido al sistema de seguimiento académico</p>
        </header>
        
        <div class="menu">
            <a href="index.php">🏠 Inicio</a>
            <a href="horario.php">📅 Horario</a>
            <a href="informacion.php">✉️ Contactar Escuela</a>
        </div>
        
        <div class="card">
            <h2>Resumen del Sistema</h2>
            <div class="stats">
                <div class="stat-box">
                    <h3>📅</h3>
                    <h4>Horario Semanal</h4>
                    <p>Consulta las clases de la semana</p>
                    <a href="horario.php">Ver Horario</a>
                </div>
                <div class="stat-box">
                    <h3>📝</h3>
                    <h4>Eventos</h4>
                    <p>Próximas actividades escolares</p>
                    <a href="horario.php#eventos">Ver Eventos</a>
                </div>
                <div class="stat-box">
                    <h3>✉️</h3>
                    <h4>Comunicación</h4>
                    <p>Contacta con la escuela</p>
                    <a href="informacion.php">Enviar Mensaje</a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2>Accesos Rápidos</h2>
            <ul>
                <li><a href="horario.php">▶ Ver horario completo</a></li>
                <li><a href="informacion.php">▶ Solicitar información académica</a></li>
                <li><a href="informacion.php">▶ Enviar justificante</a></li>
                <li><a href="informacion.php">▶ Solicitar cita</a></li>
            </ul>
        </div>
    </div>
</body>
</html>