<?php
session_start();

$paginas_publicas = ['inicias.php', 'instalar.php', 'test.php'];

$pagina_actual = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['usuario_id']) && !in_array($pagina_actual, $paginas_publicas)) 
{
    header('Location: inicias.php');
    exit();
}

if (isset($_SESSION['usuario_id']) && $pagina_actual == 'inicias.php') 
{
    header('Location: index.php');
    exit();
}
?>