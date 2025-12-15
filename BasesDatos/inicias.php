<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/log.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h1>Iniciar Sesión</h1>

    <form action="" method="POST">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        <br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>
        <br><br>

        <button type="submit" name="login">Iniciar Sesión</button>
        <button onclick="window.location.href='../index.php';">INICIO</button>
    </form>

    <?php

    if (isset($_POST['login'])) 
    {
        require '../conexion/conectadb.php';

        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña'];

        $sql = "SELECT * FROM usuarios WHERE correo = '$correo' and contraseña = '$contraseña'";
        $resultado = $conn->query($sql);

        if($resultado->num_rows == 0)
        {
            echo "Algo salió mal, verifique el correo o la contraseña.";
        }
        else
        {
            $_SESSION['correo'] = $correo;
            echo "Sesión Iniciada. <a href='../login/enviaop.php'>Ir a enviar reseña</a>";
        }
        $conn->close();
    }
    ?>
</body>
</html>
