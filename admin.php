<?php
session_start();
include "conexion.php";

if (!isset($_SESSION['autenticado']) || $_SESSION['nivel'] !== 'Administrativo') {
    header('Location: inicias.php');
    exit();
}

$mensaje = "";
$resultado_consulta = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql_query'])) {
    $sql = trim($_POST['sql_query']);
    
    try {
        if ($conn->multi_query($sql)) {
            do {
                if ($result = $conn->store_result()) {
                    $resultado_consulta = $result->fetch_all(MYSQLI_ASSOC);
                    $result->free();
                    $mensaje = "✅ Consulta SELECT ejecutada con éxito.";
                } else {
                    if ($conn->errno) {
                        $mensaje = "❌ Error: " . $conn->error;
                    } else {
                        $mensaje = "✅ Comando ejecutado. Filas afectadas: " . $conn->affected_rows;
                    }
                }
            } while ($conn->more_results() && $conn->next_result());
        } else {
            $mensaje = "❌ Error SQL: " . $conn->error;
        }
    } catch (Exception $e) {
        $mensaje = "❌ Excepción: " . $e->getMessage();
    }
}

$tablas = [];
$res = $conn->query("SHOW TABLES");
while($row = $res->fetch_array()) {
    $tablas[] = $row[0];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración SQL</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f0f2f5; margin: 0; display: flex; height: 100vh; }
        
        .sidebar { width: 250px; background: #1a2980; color: white; display: flex; flex-direction: column; }
        .sidebar-header { padding: 20px; background: #151f6d; text-align: center; font-weight: bold; font-size: 1.2em; border-bottom: 1px solid #ffffff20; }
        .sidebar-menu { flex: 1; overflow-y: auto; padding: 10px; }
        .sidebar-menu button {
            width: 100%; text-align: left; background: none; border: none; color: #ccc; padding: 10px; cursor: pointer; transition: 0.3s;
        }
        .sidebar-menu button:hover { color: white; background: rgba(255,255,255,0.1); border-radius: 5px; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #ffffff20; }
        .btn-logout { background: #dc3545; color: white; text-decoration: none; padding: 10px; display: block; text-align: center; border-radius: 5px; }
        .main { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        h2 { margin-top: 0; color: #333; }
        
        textarea { width: 100%; height: 150px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-family: monospace; font-size: 14px; background: #282c34; color: #abb2bf; resize: vertical; box-sizing: border-box; }
        
        .btn-run { background: #28a745; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; margin-top: 10px; }
        .btn-run:hover { background: #218838; }
        
        .msg-box { padding: 15px; border-radius: 5px; margin-bottom: 10px; font-weight: bold; }
        
        .table-container { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th { background: #f8f9fa; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; color: #495057; }
        td { padding: 10px; border-bottom: 1px solid #dee2e6; color: #212529; }
        tr:hover { background-color: #f8f9fa; }

    </style>
    
    <script>
        function cargarSQL(tabla) {
            document.getElementById('sql_input').value = "SELECT * FROM " + tabla + " LIMIT 50;";
        }
        function borrarSQL(tabla) {
            if(confirm('¿Seguro que quieres borrar datos de ' + tabla + '?')) {
                document.getElementById('sql_input').value = "DELETE FROM " + tabla + " WHERE id = X;";
            }
        }
    </script>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-header">
            🛠️ Admin Panel
        </div>
        <div class="sidebar-menu">
            <small style="color: #6c757d; display: block; margin-bottom: 5px; font-weight: bold;">VER TABLAS:</small>
            <?php foreach($tablas as $tabla): ?>
                <button onclick="cargarSQL('<?php echo $tabla; ?>')">📄 <?php echo $tabla; ?></button>
            <?php endforeach; ?>
        </div>
        <div class="sidebar-footer">
            <small>Admin: <?php echo $_SESSION['nombre']; ?></small>
            <br><br>
            <a href="logout.php" class="btn-logout">Cerrar Sesión</a>
        </div>
    </div>

    <div class="main">
        
        <div class="card">
            <h2>💻 Consola SQL</h2>
            <p style="color: #666; font-size: 0.9em;">Escribe tus consultas SQL aquí para administrar la base de datos (SELECT, INSERT, UPDATE, DELETE).</p>
            
            <?php if (!empty($mensaje)): ?>
                <div class="msg-box" style="background: <?php echo strpos($mensaje, 'Error') !== false ? '#f8d7da; color:#721c24' : '#d4edda; color:#155724'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <textarea name="sql_query" id="sql_input" placeholder="Ej: SELECT * FROM alumnos WHERE nivel = 'Primaria';"><?php echo isset($_POST['sql_query']) ? htmlspecialchars($_POST['sql_query']) : ''; ?></textarea>
                <div style="text-align: right;">
                    <button type="button" onclick="document.getElementById('sql_input').value=''" style="background: #6c757d; border:none; color:white; padding: 10px; border-radius: 5px; cursor: pointer; margin-right: 10px;">Limpiar</button>
                    <button type="submit" class="btn-run">▶ Ejecutar Consulta</button>
                </div>
            </form>
        </div>

        <?php if ($resultado_consulta): ?>
        <div class="card">
            <h2>📊 Resultados</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <?php 
                            foreach (array_keys($resultado_consulta[0]) as $columna): ?>
                                <th><?php echo $columna; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado_consulta as $fila): ?>
                        <tr>
                            <?php foreach ($fila as $valor): ?>
                                <td><?php echo htmlspecialchars($valor); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <h3>💡 Comandos Útiles</h3>
            <ul style="color: #555; font-size: 0.9em;">
                <li><strong>Ver alumnos:</strong> <code>SELECT * FROM alumnos;</code></li>
                <li><strong>Agregar Aviso:</strong> <code>INSERT INTO mensajes (emisor_boleta, receptor_boleta, asunto, contenido) VALUES (9999999999, 2023630289, 'Aviso', 'Texto del mensaje');</code></li>
                <li><strong>Cambiar contraseña:</strong> <code>UPDATE usuarios SET password = 'nueva123' WHERE boleta = 2023630289;</code></li>
            </ul>
        </div>

    </div>

</body>
</html>