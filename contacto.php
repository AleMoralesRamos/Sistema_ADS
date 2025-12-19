<?php
require_once 'verificar.php';
include 'conexion.php';

// Inicializar variables
$mensaje = '';
$tipo_mensaje = '';
$contactos = [];
$busqueda = '';

// Datos del formulario
$nombre = '';
$telefono = '';
$parentesco = '';
$id_contacto = 0;

// Mensajes
$MSG1 = '✅ Contacto guardado correctamente';
$MSG2 = '⚠️ Datos incompletos';
$MSG3 = '❌ Nombre obligatorio';
$MSG4 = '❌ Teléfono no válido (mínimo 10 dígitos)';
$MSG5 = '❌ Límite de contactos alcanzado (máximo 5)';
$MSG6 = '❌ Ya existe este contacto';
$MSG7 = '¿Estás seguro de eliminar este contacto?';
$MSG8 = '✅ Contacto eliminado';

$id_usuario = 1;

// Límite de contactos
$limite_contactos = 5;

if (isset($_GET['buscar']) && !empty($_GET['busqueda'])) 
{
    $busqueda = $conn->real_escape_string($_GET['busqueda']);
    $sql = "SELECT * FROM contactos_emergencia 
            WHERE id_usuario = $id_usuario 
            AND nombre_completo LIKE '%$busqueda%' 
            ORDER BY nombre_completo";
} 
else 
{
    $sql = "SELECT * FROM contactos_emergencia 
            WHERE id_usuario = $id_usuario 
            ORDER BY nombre_completo";
}

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) 
{
    while ($row = $result->fetch_assoc()) 
    {
        $contactos[] = $row;
    }
}

//Guardar y actualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $parentesco = $_POST['parentesco'];
    $id_contacto = intval($_POST['id_contacto']);
    
    $error = '';
    
    // Err1: Nombre vacío
    if (empty($nombre)) 
    {
        $error = $MSG3;
    }
    // Err2: Teléfono vacío o inválido
    elseif (empty($telefono) || strlen(preg_replace('/\D/', '', $telefono)) < 10) 
    {
        $error = $MSG4;
    }
    // Err3: Parentesco no seleccionado
    elseif (empty($parentesco)) 
    {
        $error = 'Debe seleccionar un parentesco';
    }
    // Err4: Límite de contactos 
    elseif ($id_contacto == 0) 
    {
        $count_sql = "SELECT COUNT(*) as total FROM contactos_emergencia WHERE id_usuario = $id_usuario";
        $count_result = $conn->query($count_sql);
        $count_row = $count_result->fetch_assoc();
        if ($count_row['total'] >= $limite_contactos) 
        {
            $error = $MSG5;
        }
    }
    
    // Si no hay errores, guardar en base de datos
    if (empty($error)) 
    {
        // Escapar datos para seguridad
        $nombre_esc = $conn->real_escape_string($nombre);
        $telefono_esc = $conn->real_escape_string($telefono);
        $parentesco_esc = $conn->real_escape_string($parentesco);
        
        if ($id_contacto == 0) 
        {
            // Nuevo contactos
            $sql = "INSERT INTO contactos_emergencia (id_usuario, nombre_completo, telefono, parentesco) 
                    VALUES ($id_usuario, '$nombre_esc', '$telefono_esc', '$parentesco_esc')";
        } 
        else 
        {
            // Actualizar
            $sql = "UPDATE contactos_emergencia 
                    SET nombre_completo = '$nombre_esc', 
                        telefono = '$telefono_esc', 
                        parentesco = '$parentesco_esc' 
                    WHERE id = $id_contacto AND id_usuario = $id_usuario";
        }
        
        if ($conn->query($sql) === TRUE) 
        {
            $mensaje = $MSG1;
            $tipo_mensaje = 'success';
            
            // Limpiar formulario
            $nombre = '';
            $telefono = '';
            $parentesco = '';
            $id_contacto = 0;
            
            // Recargar contactos
            header("Location: contacto.php?msg=guardado");
            exit();
        } 
        else 
        {
            $error = "Error en la base de datos: " . $conn->error;
        }
    }
    
    // Si hay error, mostrar mensaje
    if (!empty($error)) 
    {
        $mensaje = $error;
        $tipo_mensaje = 'error';
    }
}

//Eliminar
if (isset($_GET['eliminar'])) 
{
    $id_eliminar = intval($_GET['eliminar']);
    
    $sql = "DELETE FROM contactos_emergencia WHERE id = $id_eliminar AND id_usuario = $id_usuario";
    if ($conn->query($sql) === TRUE) 
    {
        header("Location: contacto.php?msg=eliminado");
        exit();
    }
}

//Editar
if (isset($_GET['editar'])) 
{
    $id_editar = intval($_GET['editar']);
    
    $sql = "SELECT * FROM contactos_emergencia WHERE id = $id_editar AND id_usuario = $id_usuario";
    $result = $conn->query($sql);
    if ($result && $result->num_rows == 1) 
    {
        $contacto = $result->fetch_assoc();
        $nombre = $contacto['nombre_completo'];
        $telefono = $contacto['telefono'];
        $parentesco = $contacto['parentesco'];
        $id_contacto = $contacto['id'];
    }
}

//Copiar
if (isset($_GET['copiar'])) 
{
    $id_copiar = intval($_GET['copiar']);
    
    $sql = "SELECT * FROM contactos_emergencia WHERE id = $id_copiar AND id_usuario = $id_usuario";
    $result = $conn->query($sql);
    if ($result && $result->num_rows == 1) 
    {
        $contacto = $result->fetch_assoc();
        $nombre = $contacto['nombre_completo'] . ' (copia)';
        $telefono = $contacto['telefono'];
        $parentesco = $contacto['parentesco'];
        $id_contacto = 0;
    }
}

//verifica el limite de contacto
$sql_count = "SELECT COUNT(*) as total FROM contactos_emergencia WHERE id_usuario = $id_usuario";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_contactos = $row_count['total'];

//muestra mensaje 
if (isset($_GET['msg'])) 
{
    if ($_GET['msg'] == 'guardado') 
    {
        $mensaje = $MSG1;
        $tipo_mensaje = 'success';
    } 
    elseif ($_GET['msg'] == 'eliminado') 
    {
        $mensaje = $MSG8;
        $tipo_mensaje = 'success';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Contactos de Emergencia</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #ff9800; padding-bottom: 10px; }
        .mensaje-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #c3e6cb; }
        .mensaje-error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #f5c6cb; }
        .nav { display: flex; gap: 10px; margin: 20px 0; flex-wrap: wrap; }
        .nav a { background-color: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .nav a:hover { background-color: #1976D2; }
        .nav .emergencia { background-color: #ff9800; }
        .form-container { background: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0; border: 1px solid #ddd; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; color: #555; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .form-group input.error { border-color: #dc3545; }
        .botones { display: flex; gap: 10px; margin-top: 20px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-guardar { background: #28a745; color: white; }
        .btn-cancelar { background: #6c757d; color: white; }
        .btn-nuevo { background: #17a2b8; color: white; }
        .btn-eliminar { background: #dc3545; color: white; }
        .btn-copiar { background: #ffc107; color: #212529; }
        .btn-actualizar { background: #007bff; color: white; }
        .busqueda { margin: 20px 0; }
        .busqueda input { width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        .busqueda button { padding: 8px 15px; background: #6c757d; color: white; border: none; border-radius: 5px; }
        .contactos-lista { margin: 20px 0; }
        .contacto-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 10px; background: white; }
        .contacto-info h4 { margin: 0; color: #333; }
        .contacto-info p { margin: 5px 0; color: #666; }
        .contacto-acciones { display: flex; gap: 10px; }
        .limite-contactos { background: #fff3e0; padding: 10px; border-radius: 5px; margin: 15px 0; border: 1px solid #ff9800; }
        .limite-contactos span { font-weight: bold; color: #ff9800; }
        .seleccionado { background-color: #e3f2fd !important; border-color: #2196F3 !important; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚨 Gestión de Contactos de Emergencia</h1>
        
        <div class="nav">
            <a href="index.php">🏠 Inicio</a>
            <a href="horario.php">📅 Horario</a>
            <a href="informacion.php">✉️ Contactar Escuela</a>
            <a href="contacto.php" class="emergencia">🚨 Contacto Emergencia</a>
        </div>
        
        <?php if ($mensaje): ?>
            <div class="mensaje-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <div class="limite-contactos">
            📊 Contactos registrados: <span><?php echo $total_contactos; ?></span> de <?php echo $limite_contactos; ?> disponibles
            <?php if ($total_contactos >= $limite_contactos): ?>
                <br><small style="color: #dc3545;">⚠️ Has alcanzado el límite máximo de contactos</small>
            <?php endif; ?>
        </div>
        
        <div class="busqueda">
            <form method="GET" action="contacto.php">
                <input type="text" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>" 
                       placeholder="Buscar contacto por nombre...">
                <button type="submit" name="buscar">🔍 Buscar</button>
                <?php if ($busqueda): ?>
                    <a href="contacto.php" style="margin-left: 10px;">❌ Limpiar búsqueda</a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if (!empty($nombre) || isset($_GET['nuevo']) || $id_contacto > 0): ?>
        <div class="form-container" id="formContacto">
            <h3>
                <?php echo ($id_contacto == 0) ? 'Nuevo Contacto de Emergencia' : 'Actualizar Contacto'; ?>
            </h3>
            
            <form method="POST" action="contacto.php">
                <input type="hidden" name="id_contacto" value="<?php echo $id_contacto; ?>">
                
                <div class="form-group">
                    <label for="nombre">Nombre completo del contacto *</label>
                    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" 
                           placeholder="Ej: María González Pérez" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono *</label>
                    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>" 
                           placeholder="Ej: 5551234567 (10 dígitos mínimo)" required>
                    <small>Formato: 10 dígitos mínimo, puede incluir guiones o espacios</small>
                </div>
                
                <div class="form-group">
                    <label for="parentesco">Parentesco *</label>
                    <select id="parentesco" name="parentesco" required>
                        <option value="">- - - - - Seleccione - - - - -</option>
                        <option value="Madre" <?php echo ($parentesco == 'Madre') ? 'selected' : ''; ?>>Madre</option>
                        <option value="Padre" <?php echo ($parentesco == 'Padre') ? 'selected' : ''; ?>>Padre</option>
                        <option value="Tutor" <?php echo ($parentesco == 'Tutor') ? 'selected' : ''; ?>>Tutor</option>
                        <option value="Abuelo/a" <?php echo ($parentesco == 'Abuelo/a') ? 'selected' : ''; ?>>Abuelo/a</option>
                        <option value="Tío/a" <?php echo ($parentesco == 'Tío/a') ? 'selected' : ''; ?>>Tío/a</option>
                        <option value="Hermano/a" <?php echo ($parentesco == 'Hermano/a') ? 'selected' : ''; ?>>Hermano/a</option>
                        <option value="Otro" <?php echo ($parentesco == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                    </select>
                </div>
                
                <div class="botones">
                    <button type="submit" class="btn btn-guardar" name="guardar">
                        💾 <?php echo ($id_contacto == 0) ? 'Guardar' : 'Actualizar'; ?>
                    </button>
                    <a href="contacto.php" class="btn btn-cancelar">❌ Cancelar</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <?php if ($total_contactos < $limite_contactos && !isset($_GET['nuevo']) && $id_contacto == 0): ?>
        <div class="botones" style="margin: 20px 0;">
            <a href="contacto.php?nuevo=1" class="btn btn-nuevo">
                ➕ Crear nuevo contacto
            </a>
        </div>
        <?php endif; ?>
        
        <div class="contactos-lista">
            <h3>📋 Lista de Contactos de Emergencia</h3>
            
            <?php if (empty($contactos)): ?>
                <div class="mensaje-error">
                    No hay contactos registrados. 
                    <?php if ($total_contactos < $limite_contactos): ?>
                        <a href="contacto.php?nuevo=1">Haz clic aquí para agregar uno</a>.
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($contactos as $contacto): ?>
                    <div class="contacto-item">
                        <div class="contacto-info">
                            <h4><?php echo htmlspecialchars($contacto['nombre_completo']); ?></h4>
                            <p>📞 <?php echo htmlspecialchars($contacto['telefono']); ?> | 
                               👨‍👩‍👧‍👦 <?php echo htmlspecialchars($contacto['parentesco']); ?> | 
                               📅 <?php echo date('d/m/Y', strtotime($contacto['fecha_registro'])); ?></p>
                        </div>
                        
                        <div class="contacto-acciones">
                            <a href="contacto.php?editar=<?php echo $contacto['id']; ?>" class="btn btn-actualizar">
                                ✏️ Actualizar
                            </a>
                            <a href="contacto.php?copiar=<?php echo $contacto['id']; ?>" class="btn btn-copiar">
                                📋 Copiar
                            </a>
                            <a href="contacto.php?eliminar=<?php echo $contacto['id']; ?>" 
                               class="btn btn-eliminar"
                               onclick="return confirm('<?php echo $MSG7; ?>')">
                                🗑️ Eliminar
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Validación del teléfono
        document.getElementById('telefono')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length < 10) {
                e.target.style.borderColor = '#dc3545';
            } else {
                e.target.style.borderColor = '#28a745';
            }
        });
    </script>
</body>
</html>