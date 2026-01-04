<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    header('Location: inicias.php');
    exit();
}

$mi_boleta = $_SESSION['boleta'];
$mi_nombre = $_SESSION['nombre'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['contenido'])) {
    $receptor_destino = $_POST['receptor']; 
    $asunto           = $_POST['asunto'];
    $contenido        = $_POST['contenido'];

    $sqlInsert = "INSERT INTO mensajes (emisor_boleta, receptor_boleta, asunto, contenido) VALUES (?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("ssss", $mi_boleta, $receptor_destino, $asunto, $contenido);
    
    if($stmtInsert->execute()){
        echo "<script>alert('✅ Respuesta enviada correctamente');</script>";
        echo "<meta http-equiv='refresh' content='0'>"; 
    } else {
        echo "<script>alert('❌ Error al enviar');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bandeja de Mensajes</title>
    <link rel="stylesheet" href="./css/estilo3.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; }
        
        .system-header { background: linear-gradient(135deg, #2e7d32, #4CAF50); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        
        .system-menu { background: white; padding: 15px 30px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .system-menu a { background-color: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block; margin-bottom: 5px;}
        
        .container { max-width: 900px; margin: 0 auto; padding: 20px; }

        .mensaje-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 5px solid #4CAF50; 
            overflow: hidden;
        }

        .mensaje-header {
            background: #e8f5e9;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mensaje-header h3 { margin: 0; color: #2e7d32; font-size: 1.2em; }
        .mensaje-meta { font-size: 0.9em; color: #666; }

        .mensaje-body { padding: 20px; color: #333; line-height: 1.6; }

        .respuesta-box {
            background: #fafafa;
            padding: 15px;
            border-top: 1px solid #eee;
            display: none; 
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
            font-family: Arial, sans-serif;
        }

        .btn-responder {
            background-color: #2196F3; color: white; border: none; padding: 8px 15px; 
            border-radius: 4px; cursor: pointer; font-size: 0.9em;
        }
        
        .btn-enviar {
            background-color: #4CAF50; color: white; border: none; padding: 10px 20px; 
            border-radius: 4px; cursor: pointer; margin-top: 10px; font-weight: bold;
        }
        
        .btn-toggle {
            background: none; border: none; color: #2196F3; cursor: pointer; text-decoration: underline;
        }
    </style>
    
    <script>
        function mostrarResponder(id) {
            var box = document.getElementById('resp-' + id);
            if (box.style.display === 'block') {
                box.style.display = 'none';
            } else {
                box.style.display = 'block';
            }
        }
    </script>
</head>
<body>

    <div class="system-header">
        <div>
            <h2 style="margin: 0;">📩 Bandeja de Entrada</h2>
            <small>👤 <?php echo $mi_nombre; ?> | Boleta: <?php echo $mi_boleta; ?></small>
        </div>
        <a href="logout.php" style="background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">Cerrar Sesión</a>
    </div>

    <div class="system-menu">
        <a href="index.php">🏠 Inicio</a>
        <a href="horario.php">📅 Horario</a>
        <a href="calif.php">📊 Calificaciones</a>
        <a href="informacion.php">✉️ Contactar Escuela</a>
        <a href="mensaje_situacion.php" style="background: #4CAF50;">📩 Mis Mensajes</a>
    </div>

    <div class="container">
        
        <?php
        $sqlSelect = "
            SELECT 
                m.id,
                m.asunto,
                m.contenido,
                m.fecha,
                m.emisor_boleta,
                a.nombre,
                a.apellidos
            FROM mensajes m
            LEFT JOIN alumnos a ON m.emisor_boleta = a.boleta
            WHERE m.receptor_boleta = ?
            ORDER BY m.fecha DESC
        ";

        $stmtSelect = $conn->prepare($sqlSelect);
        $stmtSelect->bind_param("s", $mi_boleta);
        $stmtSelect->execute();
        $resultado = $stmtSelect->get_result();

        if ($resultado->num_rows === 0) {
            echo "<div style='text-align:center; padding: 40px; background: white; border-radius: 8px;'>
                    <h3>📭 No tienes mensajes nuevos.</h3>
                    <p>Tu bandeja de entrada está vacía.</p>
                  </div>";
        }

        while ($fila = $resultado->fetch_assoc()) {
            $nombre_emisor = $fila['nombre'] ? $fila['nombre'] . " " . $fila['apellidos'] : "Usuario " . $fila['emisor_boleta'];
            $fecha_formato = date("d/m/Y h:i A", strtotime($fila['fecha']));
        ?>
            <div class="mensaje-card">
                <div class="mensaje-header">
                    <div>
                        <h3><?= htmlspecialchars($fila['asunto']) ?></h3>
                        <div class="mensaje-meta">
                            De: <strong><?= htmlspecialchars($nombre_emisor) ?></strong>
                        </div>
                    </div>
                    <div class="mensaje-meta">
                        <?= $fecha_formato ?>
                    </div>
                </div>

                <div class="mensaje-body">
                    <?= nl2br(htmlspecialchars($fila['contenido'])) ?>
                </div>
                
                <div style="padding: 0 15px 15px 15px; text-align: right;">
                    <button class="btn-responder" onclick="mostrarResponder(<?= $fila['id'] ?>)">↩ Responder</button>
                </div>

                <div class="respuesta-box" id="resp-<?= $fila['id'] ?>">
                    <form method="POST">
                        <input type="hidden" name="receptor" value="<?= $fila['emisor_boleta'] ?>">
                        <input type="hidden" name="asunto" value="Re: <?= htmlspecialchars($fila['asunto']) ?>">

                        <label><strong>Tu respuesta:</strong></label>
                        <textarea name="contenido" rows="3" placeholder="Escribe tu respuesta aquí..." required></textarea>
                        
                        <button type="submit" class="btn-enviar">✈️ Enviar Respuesta</button>
                    </form>
                </div>
            </div>
        <?php } ?>
        
        <br>
        <div style="text-align: center;">
            <a href="index.php" style="color: #4CAF50; text-decoration: none; font-weight: bold;">⬅ Volver al Inicio</a>
        </div>
    </div>

</body>
</html>