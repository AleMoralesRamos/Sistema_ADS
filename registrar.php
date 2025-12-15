<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/registro.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrate</title>
</head>
<body>
    <h1>Registro de Usuarios</h1>

    <form action="" method="POST">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required>
        <br><br>

        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>
        <br><br>

        <button type="submit" name="submit">Añadir Usuario</button>
        <button onclick="window.location.href='../index.php';">INICIO</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        
        require '../conexion/conectadb.php';

        $correo = $_POST['correo'];
        $contraseña = $_POST['contraseña'];

        $sql_ver = "Select * from usuarios where correo = '$correo'";
        $resultado = $conn->query($sql_ver);

        if($resultado->num_rows > 0)
        {
            echo "<p style='color:red;'>Error: El correo ya está registrado.</p>";
        }

        else{

        $sql = "INSERT INTO usuarios (correo, contraseña) VALUES ('$correo', '$contraseña')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Usuario añadido exitosamente.</p>";
        } else {
            echo "<p style='color:red;'>Error al añadir usuario: " . $conn->error . "</p>";
        }
    
        $conn->close();
    }
}
    ?>
</body>
</html>
