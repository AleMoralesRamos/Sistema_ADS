<?php
session_start();
include 'conexion.php';

$mensaje = '';
$paso = 1;
$boleta_recuperacion = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['verificar'])) {
        $boleta_input = trim($_POST['boleta']);
        $email_input = trim($_POST['email']);
        
        $sql = "SELECT boleta FROM usuarios WHERE boleta = ? AND email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $boleta_input, $email_input);
        $stmt->execute();
        $res = $stmt->get_result();
        
        if ($res->num_rows > 0) {
            $paso = 2;
            $boleta_recuperacion = $boleta_input;
            $mensaje = "✅ Datos verificados. Ahora crea tu nueva contraseña.";
        } else {
            $mensaje = "❌ Error: La boleta o el correo no coinciden.";
        }
    }
    
    if (isset($_POST['cambiar_pass'])) {
        $boleta_final = $_POST['boleta_hidden'];
        $nueva_pass = $_POST['new_password'];
        $confirmar_pass = $_POST['confirm_password'];
        
        if (!empty($nueva_pass) && $nueva_pass === $confirmar_pass) {
            $sql = "UPDATE usuarios SET password = ? WHERE boleta = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nueva_pass, $boleta_final);
            
            if ($stmt->execute()) {
                $mensaje = "✅ ¡Contraseña actualizada exitosamente!";
                $paso = 3; 
            } else {
                $mensaje = "❌ Error al actualizar en la base de datos.";
                $paso = 2;
                $boleta_recuperacion = $boleta_final;
            }
        } else {
            $mensaje = "❌ Las contraseñas no coinciden o están vacías.";
            $paso = 2;
            $boleta_recuperacion = $boleta_final;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        h2 { color: #1a2980; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2196F3; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background: #1976D2; }
        .msg { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; }
        .error { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        .success { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        a { color: #2196F3; text-decoration: none; font-size: 14px; display: block; margin-top: 15px; }
    </style>
</head>
<body>

    <div class="card">
        <h2>🔐 Recuperar Acceso</h2>

        <?php if (!empty($mensaje)): ?>
            <div class="msg <?php echo strpos($mensaje, '❌') !== false ? 'error' : 'success'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <?php if ($paso == 1): ?>
            <form method="POST">
                <p style="font-size: 14px; color: #666;">Ingresa tu boleta y el correo registrado para validar tu identidad.</p>
                <input type="text" name="boleta" placeholder="Boleta del Alumno" required>
                <input type="email" name="email" placeholder="Correo Registrado" required>
                <button type="submit" name="verificar">Verificar Identidad</button>
            </form>
        
        <?php elseif ($paso == 2): ?>
            <form method="POST">
                <p style="font-size: 14px; color: #666;">Identidad verificada. Crea una nueva contraseña.</p>
                <input type="hidden" name="boleta_hidden" value="<?php echo htmlspecialchars($boleta_recuperacion); ?>">
                <input type="password" name="new_password" placeholder="Nueva Contraseña" required>
                <input type="password" name="confirm_password" placeholder="Confirmar Nueva Contraseña" required>
                <button type="submit" name="cambiar_pass" style="background: #4CAF50;">Guardar Contraseña</button>
            </form>

        <?php elseif ($paso == 3): ?>
            <p>Tu contraseña ha sido restablecida correctamente.</p>
            <a href="inicias.php" style="background: #1a2980; color: white; padding: 10px; border-radius: 5px; text-decoration: none;">Ir a Iniciar Sesión</a>
        <?php endif; ?>

        <?php if ($paso != 3): ?>
            <a href="inicias.php">⬅ Volver al Login</a>
        <?php endif; ?>
    </div>

</body>
</html>