<?php
require 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['autenticado']) && $_SESSION['autenticado'] === true) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
        
        .error { 
            color: #721c24; 
            background: #f8d7da; 
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #721c24;
            margin: 15px 0;
            text-align: center;
        }
        
        .success { 
            color: #155724; 
            background: #d4edda; 
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #155724;
            margin: 15px 0;
            text-align: center;
        }
        
        .form-container {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <h1> Sistema Escolar - Iniciar Sesión</h1>
    
    <div class="form-container">
        <form action="" method="POST">
            <label for="boleta">Boleta:</label>
            <input type="text" id="boleta" name="boleta" required placeholder="Ingresa tu número de boleta">
            
            <label for="contraseña">Contraseña:</label>
            <input type="password" id="contraseña" name="contraseña" required placeholder="Ingresa tu contraseña">
            
            <button type="submit" name="login"> Iniciar Sesión</button>
            <div style="margin-top: 15px; text-align: center;">
                <a href="recuperar.php" style="color: #666; font-size: 14px; text-decoration: none;">¿Olvidaste tu contraseña?</a>
            </div>
            
            <?php
            if (isset($_POST['login'])) 
            {

                $boleta = $_POST['boleta'];
                $password_input = $_POST['contraseña'];

                if (function_exists('verificarUsuario')) {
                    $loginExitoso = verificarUsuario($boleta, $password_input);
                    
                   if (function_exists('verificarUsuario')) {
    $loginExitoso = verificarUsuario($boleta, $password_input);
    
    if ($loginExitoso) {
        $nombre = $_SESSION['nombre']; 
        echo "<div class='success'> Bienvenido $nombre, redirigiendo...</div>";
        
        if ($_SESSION['nivel'] == 'Administrativo') {
            echo "<meta http-equiv='refresh' content='1;url=admin.php'>";
        } 
        else {
            echo "<meta http-equiv='refresh' content='1;url=index.php'>";
        }
        exit();
    } else {
        echo "<div class='error'> Boleta o contraseña incorrecta.</div>";
    }
}
                } 
                else {
                    $sql = "SELECT u.boleta, u.password, a.nombre, a.apellidos, a.nivel 
                            FROM usuarios u 
                            LEFT JOIN alumnos a ON u.boleta = a.boleta 
                            WHERE u.boleta = '$boleta'";
                    
                    $resultado = $conn->query($sql);

                    if ($resultado && $resultado->num_rows > 0) {
                        $usuario = $resultado->fetch_assoc();
                        
                        // Verificar contraseña
                        if ($usuario['password'] === $password_input) {
                            $_SESSION['boleta'] = $usuario['boleta'];
                            $_SESSION['nombre'] = $usuario['nombre'] . " " . $usuario['apellidos'];
                            $_SESSION['nivel'] = $usuario['nivel'];
                            $_SESSION['autenticado'] = true;
                            
                            echo "<div class='success'> Bienvenido " . $usuario['nombre'] . "</div>";
                            echo "<meta http-equiv='refresh' content='1;url=index.php'>";
                            exit();
                        } else {
                            echo "<div class='error'>Contraseña incorrecta.</div>";
                        }
                    } else {
                        echo "<div class='error'> La boleta no existe.</div>";
                    }
                }
                
                $conn->close();
            }
            ?>
        </form>
    </div>
</body>
</html>