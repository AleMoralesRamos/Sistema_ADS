<?php
function cntRenglon($datos) {
    $clave           = $datos['clave'] ?? '';
    $materia         = $datos['materia'] ?? '';
    $periodo         = $datos['periodo'] ?? '';
    $formaEvaluacion = $datos['forma_evaluacion'] ?? '';
    $calificacion    = $datos['calificacion'] ?? '';
    $estado          = $datos['estado'] ?? '';

    /* Recalcular estado */
    if (empty($calificacion) || is_null($calificacion)) {
        $estado = 'Sin cursar';
    } elseif ($calificacion >= 6) {
        $estado = 'Aprobada';
    } else {
        $estado = 'Reprobada';
    }

    // Formatear calificación
    $calif_display = (!is_null($calificacion) && $calificacion !== '') ? $calificacion : '-';

    return "
        <tr>
            <td>$clave</td>
            <td>$materia</td>
            <td>$periodo</td>
            <td>$formaEvaluacion</td>
            <td>$calif_display</td>
            <td>$estado</td>
            <td></td> <!-- Columna vacía para el promedio semestral -->
        </tr>
    ";
}
?>