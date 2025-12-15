<?php
    include 'conexion.php';
    $cons_horario = "SELECT*FROM horarios ORDER BY dia, inicio";
    $res_horario = $conn->query($cons_horario);

    $cons_cal = "SELECT*FROM calendario_eventos WHERE fecha >= CURDATE() ORDER BY ASC LIMIT 5";
    $res_cal = $conn->query($cons_cal);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Horario y Calendario</title>
    <style>
        .contenedor{display: flex; gap: 20px;}
        .caja{border: 1px solid #ccc; padding: 15px; width: 50%;}
        table{width: 100%; border-collapse: collapse;}
        th, td{border: 1px solid #ddd; padding: 8px; text-align: left;}
        th{background-color: #f2f2f2;}
    </style>
</head>

<body>

    <h1>Agenda del Estudiante</h1>

    <div class="contenedor">
        
        <div class="caja">
            <h2>Horario Semanal</h2>
            <table>
                <tr>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Materia</th>
                </tr>
                <?php while($clase = $res_horario->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $clase['dia']; ?></td>
                    <td><?php echo date('H:i', strtotime($clase['hora_inicio'])) . ' - ' . date('H:i', strtotime($clase['hora_fin'])); ?></td>
                    <td><?php echo $clase['materia']; ?> (<?php echo $clase['profesor']; ?>)</td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <div class="caja" style="background-color: #fff8e1;">
            <h2>Próximos Eventos</h2>
            <ul>
                <?php while($evento = $res_calendario->fetch_assoc()): ?>
                <li>
                    <strong><?php echo $evento['fecha']; ?>:</strong> 
                    <?php echo $evento['evento']; ?> 
                    <small>(<?php echo $evento['tipo']; ?>)</small>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>

    </div>
    <br>
    <a href="index.php">⬅ Volver al Inicio</a>
</body>
</html>