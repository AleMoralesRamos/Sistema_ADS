<?php
session_start();

if (isset($_SESSION['usuario_id'])) 
{
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/log.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        h1 {
            text-align: center;
            color: white;
            margin-bottom: 30px;
            font-size: 28px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        form { 
            background: white;
            max-width: 400px;
            width: 90%;
            margin: 0 auto;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        
        input { 
            width: 100%; 
            padding: 12px 15px;
            margin: 5px 0 20px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border 0.3s;
        }
        
        input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        
        button { 
            width: 100%; 
            padding: 14px;
            margin: 10px 0 0 0;
            background: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 8px;
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
            transition: background 0.3s, transform 0.2s;
        }
        
        button:hover {
            background: #45a049;
            transform: translateY(-2px);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .error { 
            color: red; 
            background: #ffe6e6; 
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid red;
            margin: 15px 0;
        }
        
        .success { 
            color: green; 
            background: #e6ffe6; 
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid green;
            margin: 15px 0;
        }
        
        .form-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <h1>📚 Sistema Escolar - Iniciar Sesión</h1>
    
    <div class="form-container">
        <form action="" method="POST">
            <label for="boleta">Boleta:</label>
            <input type="text" id="boleta" name="boleta" required placeholder="Ingresa tu número de boleta">
            
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required placeholder="Ingresa tu contraseña">
            
            <button type="submit" name="login">🔐 Iniciar Sesión</button>
        </form>
    </div>

    <?php
    if (isset($_POST['login'])) 
    {
        require 'conexion.php';

        $correo = $_POST['boleta'];
        $contraseña = $_POST['contraseña'];

        $sql = "SELECT * FROM usuarios WHERE boleta = '$correo' and contraseña = '$contraseña'";
        $resultado = $conn->query($sql);

        if($resultado->num_rows == 0)
        {
            echo "<div class='error'>❌ Algo salió mal, verifique la boleta o la contraseña.</div>";
        }
        else
        {
             $usuario = $resultado->fetch_assoc();
            
            // Guardar datos en sesión
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['boleta'] = $usuario['boleta'];
            $_SESSION['nombre'] = $usuario['nombre']; 
            $_SESSION['tipo'] = $usuario['tipo'];

            echo "<div class='success'>✅ Sesión iniciada. Redirigiendo...</div>";
            header('Refresh: 1; URL=index.php'); 
            exit();
        }
        $conn->close();
    }
    ?>
</body>
</html>