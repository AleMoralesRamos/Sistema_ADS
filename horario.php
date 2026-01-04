<?php
session_start();

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: inicias.php');
    exit();
}

include 'conexion.php';

$nivel_alumno = $_SESSION['nivel']; 
$nombre = $_SESSION['nombre'];

$cons_horario = "SELECT * FROM horarios 
                 WHERE nivel = '$nivel_alumno'
                 ORDER BY 
                 FIELD(dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), 
                 hora_inicio ASC";
$res_horario = $conn->query($cons_horario);

$cons_cal = "SELECT * FROM calendario_eventos 
             WHERE fecha >= CURDATE() 
             ORDER BY fecha ASC LIMIT 5";
$res_cal = $conn->query($cons_cal);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario y Calendario</title>
    <link rel="stylesheet" href="./css/estilo3.css"> 
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; }
        
        .system-header { 
            background: linear-gradient(135deg, #1a2980, #26d0ce); 
            color: white; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        
        .system-menu { background: white; padding: 15px 30px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .system-menu a { background-color: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block; margin-bottom: 5px; }
        .system-menu a:hover { background-color: #1976D2; }
        
        .contenedor-principal {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .columna {
            flex: 1;
            min-width: 300px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        h2 { border-bottom: 2px solid #4CAF50; padding-bottom: 10px; color: #2e7d32; margin-top: 0; }
        
        /* Tabla de Horarios */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4CAF50; color: white; padding: 10px; text-align: left; }
        td { border-bottom: 1px solid #eee; padding: 10px; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        
        /* Lista de Eventos */
        .eventos-list { list-style: none; padding: 0; }
        .evento-item { 
            padding: 15px; 
            border-bottom: 1px solid #eee; 
            display: flex; 
            align-items: center; 
            gap: 15px; 
        }
        .fecha-badge {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 5px 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
            min-width: 60px;
        }
        .etiqueta {
            font-size: 0.8em;
            padding: 3px 8px;
            border-radius: 10px;
            color: white;
            display: inline-block;
            margin-top: 5px;
        }
        .tag-examen { background-color: #dc3545; }
        .tag-suspension { background-color: #ff9800; }
        .tag-evento { background-color: #2196F3; }
        
        .mensaje-vacio { color: #666; font-style: italic; padding: 20px; text-align: center; }
    </style>
</head>

<body>

    <div class="system-header">
        <div>
            <h2 style="margin: 0; border: none; color: white;">📅 Agenda Escolar</h2>
            <small>👤 <?php echo $nombre; ?> | Nivel: <?php echo $nivel_alumno; ?></small>
        </div>
        <a href="logout.php" style="background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">Cerrar Sesión</a>
    </div>

    <div class="system-menu">
        <a href="index.php">🏠 Inicio</a>
        <a href="horario.php" style="background: #4CAF50;">📅 Horario</a>
        <a href="calif.php">📊 Calificaciones</a>
        <a href="informacion.php">✉️ Contactar Escuela</a>
        <a href="contacto.php">🚨 Contacto Emergencia</a>
    </div>

    <div class="contenedor-principal">
        
        <div class="columna">
            <h2>⏰ Horario de Clases (<?php echo $nivel_alumno; ?>)</h2>
            
            <?php if ($res_horario && $res_horario->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th width="20%">Día</th>
                            <th width="25%">Hora</th>
                            <th>Materia y Profesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($clase = $res_horario->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: bold; color: #1b5e20;">
                                <?php echo htmlspecialchars($clase['dia']); ?>
                            </td>
                            <td>
                                🕒 <?php echo date('H:i', strtotime($clase['hora_inicio'])) . ' - ' . date('H:i', strtotime($clase['hora_fin'])); ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($clase['materia']); ?></strong>
                                <br>
                                <small style="color: #666;">👨‍🏫 <?php echo htmlspecialchars($clase['profesor']); ?></small>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="mensaje-vacio">
                    <p>📭 No hay horarios registrados para tu nivel educativo.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="columna" style="max-width: 400px;">
            <h2>📅 Próximos Eventos</h2>
            
            <?php if ($res_cal && $res_cal->num_rows > 0): ?>
                <div class="eventos-list">
                    <?php while($evento = $res_cal->fetch_assoc()): 
                        $clase_tag = 'tag-evento';
                        if($evento['tipo'] == 'Examen') $clase_tag = 'tag-examen';
                        if($evento['tipo'] == 'Suspensión') $clase_tag = 'tag-suspension';
                    ?>
                    <div class="evento-item">
                        <div class="fecha-badge">
                            <?php echo date('d', strtotime($evento['fecha'])); ?><br>
                            <small><?php echo strtoupper(date('M', strtotime($evento['fecha']))); ?></small>
                        </div>
                        <div>
                            <div style="font-weight: bold; font-size: 1.1em;">
                                <?php echo htmlspecialchars($evento['evento']); ?>
                            </div>
                            <span class="etiqueta <?php echo $clase_tag; ?>">
                                <?php echo htmlspecialchars($evento['tipo']); ?>
                            </span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="mensaje-vacio">
                    <p>✨ No hay eventos próximos en el calendario.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
    <a href="index.php" style="color: #2196F3; text-decoration: none;">⬅ Volver al Inicio</a>

</body>
</html>