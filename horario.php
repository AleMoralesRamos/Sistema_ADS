<?php
require_once 'verificar.php';
include 'conexion.php';

$cons_horario = "SELECT * FROM horarios ORDER BY 
    FIELD(dia, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), 
    ini ASC";
$res_horario = $conn->query($cons_horario);

$cons_cal = "SELECT * FROM calendario_eventos WHERE fecha >= CURDATE() ORDER BY fecha ASC LIMIT 5";
$res_cal = $conn->query($cons_cal);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Horario y Calendario</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .contenedor{display: flex; gap: 20px; flex-wrap: wrap;}
        .caja{border: 1px solid #ccc; padding: 15px; width: 48%; box-shadow: 0 2px 5px rgba(0,0,0,0.1);}
        table{width: 100%; border-collapse: collapse; margin-top: 10px;}
        th, td{border: 1px solid #ddd; padding: 10px; text-align: left;}
        th{background-color: #4CAF50; color: white;}
        tr:nth-child(even){background-color: #f9f9f9;}
        h1{color: #333;}
        .eventos-list { list-style-type: none; padding: 0; }
        .eventos-list li { padding: 10px; border-bottom: 1px solid #eee; }
        .eventos-list li:last-child { border-bottom: none; }
        a { color: #2196F3; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <h1>📅 Agenda del Estudiante</h1>
    
    <div style="margin-bottom: 20px;">
        <a href="index.php">🏠 Inicio</a> | 
        <a href="horario.php">📅 Horario</a> | 
        <a href="informacion.php">✉️ Contactar Escuela</a>
    </div>

    <div class="contenedor">
        
        <div class="caja">
            <h2>⏰ Horario Semanal</h2>
            <?php if ($res_horario && $res_horario->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Materia</th>
                </tr>
                <?php while($clase = $res_horario->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($clase['dia']); ?></strong></td>
                    <td><?php echo date('H:i', strtotime($clase['ini'])) . ' - ' . date('H:i', strtotime($clase['fin'])); ?></td>
                    <td><?php echo htmlspecialchars($clase['materia']); ?> 
                        <br><small><?php echo htmlspecialchars($clase['profesor']); ?></small>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
            <?php else: ?>
            <p>No hay horarios registrados.</p>
            <?php endif; ?>
        </div>

        <div class="caja" style="background-color: #fff8e1;">
            <h2>📅 Próximos Eventos</h2>
            <?php if ($res_cal && $res_cal->num_rows > 0): ?>
            <ul class="eventos-list">
                <?php while($evento = $res_cal->fetch_assoc()): ?>
                <li>
                    <strong><?php echo date('d/m/Y', strtotime($evento['fecha'])); ?>:</strong><br>
                    <span style="font-size: 1.1em;"><?php echo htmlspecialchars($evento['evento']); ?></span><br>
                    <small style="color: #666;">🏷️ <?php echo htmlspecialchars($evento['tipo']); ?></small>
                </li>
                <?php endwhile; ?>
            </ul>
            <?php else: ?>
            <p>No hay eventos próximos.</p>
            <?php endif; ?>
        </div>

    </div>
    
    <br>
    <a href="index.php">⬅ Volver al Inicio</a>
    
    <?php
    if ($res_horario) $res_horario->free();
    if ($res_cal) $res_cal->free();
    ?>
</body>
</html>