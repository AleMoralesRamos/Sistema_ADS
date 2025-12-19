<?php
include "conexion.php";

/*
  Simulación de usuario autenticado
  En un sistema real vendría de $_SESSION['usuario_id']
*/
$usuario_id = 2; // Padre Juan

// ================== PROCESAR RESPUESTA ==================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $receptor  = $_POST['receptor'];
    $asunto    = $_POST['asunto'];
    $contenido = $_POST['contenido'];

    $sqlInsert = "
        INSERT INTO mensajes (emisor_id, receptor_id, asunto, contenido)
        VALUES (?, ?, ?, ?)
    ";

    $stmtInsert = $conexion->prepare($sqlInsert);
    $stmtInsert->bind_param("iiss", $usuario_id, $receptor, $asunto, $contenido);
    $stmtInsert->execute();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bandeja de mensajes</title>
    <link rel="stylesheet" href="estilo3.css">
</head>
<body>

<header>
    BANDEJA DE MENSAJES
</header>

<main>
<?php
// ================== OBTENER MENSAJES ==================
$sqlSelect = "
    SELECT 
        m.asunto,
        m.contenido,
        m.fecha,
        m.emisor_id,
        u.nombre AS emisor
    FROM mensajes m
    JOIN usuarios u ON m.emisor_id = u.id
    WHERE m.receptor_id = ?
    ORDER BY m.fecha DESC
";

$stmtSelect = $conexion->prepare($sqlSelect);
$stmtSelect->bind_param("i", $usuario_id);
$stmtSelect->execute();
$resultado = $stmtSelect->get_result();

if ($resultado->num_rows === 0) {
    echo "<p style='text-align:center;'>No tienes mensajes.</p>";
}

while ($fila = $resultado->fetch_assoc()) {
?>
    <section class="semestre">
        <h2><?= htmlspecialchars($fila['asunto']) ?></h2>

        <div class="table-wrapper">
            <div class="mensaje-info">
                De: <b><?= htmlspecialchars($fila['emisor']) ?></b> |
                <?= $fila['fecha'] ?>
            </div>

            <div class="mensaje-contenido">
                <?= nl2br(htmlspecialchars($fila['contenido'])) ?>
            </div>

            <!-- FORMULARIO DE RESPUESTA -->
            <form method="POST">
                <input type="hidden" name="receptor" value="<?= $fila['emisor_id'] ?>">
                <input type="hidden" name="asunto" value="Re: <?= htmlspecialchars($fila['asunto']) ?>">

                <textarea name="contenido" rows="4"
                          placeholder="Escribe tu respuesta aquí..."
                          required></textarea><br><br>

                <button type="submit">Responder</button>
            </form>
        </div>
    </section>
<?php } ?>
</main>

<!-- BOTÓN REGRESAR A INICIO -->
<footer>
    <form action="inicio.php" method="get">
        <button type="submit">Regresar a inicio</button>
    </form>
</footer>

</body>
</html>
