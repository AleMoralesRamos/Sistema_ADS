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
    <link rel="stylesheet" href="estilo3.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; }
        
        .system-header { background: linear-gradient(135deg, #2e7d32, #4CAF50); color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .system-menu { background: white; padding: 15px 30px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .system-menu a { background-color: #2196F3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        
        .nivel { 
            background: #e8f5e9; 
            padding: 15px; 
            border-left: 6px solid #4CAF50; 
            margin-top: 25px; 
            border-radius: 4px; 
            page-break-after: avoid; 
        }
        
        .nivel h2 { margin: 0; color: #1b5e20; }
        
        .promedio-general { 
            background: #fff; 
            padding: 10px; 
            margin-bottom: 15px; 
            font-weight: bold; 
            text-align: right; 
            color: #2e7d32; 
            border-bottom: 2px solid #e8f5e9; 
        }
        
        .semestre { 
            margin-top: 15px; 
            border: 1px solid #ddd; 
            background: white; 
            padding: 15px; 
            border-radius: 5px; 
            page-break-inside: avoid; 
        }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        tr { page-break-inside: avoid; }
        th { background-color: #4CAF50; color: white; padding: 12px; text-align: left; }
        td { padding: 10px 12px; border-bottom: 1px solid #eee; }

        .btn-pdf {
            background-color: #d32f2f;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-pdf:hover { background-color: #b71c1c; }
        
        #header-pdf { display: none; }
    </style>
</head>
<body>

    <div class="system-header">
        <div>
            <h2 style="margin: 0;"> Sistema Escolar</h2>
            <small> <?php echo $nombre; ?> | Boleta: <?php echo $boleta; ?></small>
        </div>
        <a href="logout.php" style="background: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">Cerrar Sesión</a>
    </div>

    <div class="system-menu">
        <a href="index.php"> Inicio</a>
        <a href="horario.php"> Horario</a>
        <a href="calif.php" style="background: #4CAF50;"> Calificaciones</a>
        <a href="informacion.php"> Contactar Escuela</a>
        <a href="contacto.php"> Contacto Emergencia</a>
    </div>

    <div class="container">
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Historial Académico</h1>
            <button onclick="generarPDF()" class="btn-pdf"> Descargar Boleta PDF</button>
        </div>
        
        <div id="area-imprimir">
            <div id="header-pdf" style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: #2e7d32; margin:0;">BOLETA DE CALIFICACIONES</h2>
                <p><strong>Alumno:</strong> <?php echo $nombre; ?> | <strong>Boleta:</strong> <?php echo $boleta; ?></p>
                <hr style="border: 1px solid #4CAF50;">
            </div>

            <?php require('tabla.php'); ?>
            
            <div style="margin-top: 30px; text-align: center; font-size: 0.8em; color: #666;">
                <p>Fecha de emisión: <?php echo date('d/m/Y'); ?></p>
            </div>
        </div>
        
        <br>
        <a href="index.php" style="color: #2196F3; text-decoration: none;">⬅ Volver al Inicio</a>
    </div>

    <script>
        function generarPDF() {
            const elemento = document.getElementById('area-imprimir');
            document.getElementById('header-pdf').style.display = 'block';

            const opciones = {
                margin:       10,
                filename:     'Boleta_<?php echo $boleta; ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['css', 'legacy'] }
            };

            html2pdf()
                .set(opciones)
                .from(elemento)
                .save()
                .then(() => {
                    document.getElementById('header-pdf').style.display = 'none';
                });
        }
    </script>

</body>
</html>