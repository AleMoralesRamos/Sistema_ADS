<?php

function cntRenglon($datos) {

    $clave           = $datos['clave'];
    $materia         = $datos['materia'];
    $periodo         = $datos['periodo'];
    $formaEvaluacion = $datos['forma_evaluacion'];
    $calificacion    = $datos['calificacion'];
    $estado          = $datos['estado'];

    /* Recalcular estado (misma lógica que antes) */
    if (is_null($calificacion)) {
        $estado = 'Sin cursar';
    } elseif ($calificacion >= 6) {
        $estado = 'Aprobada';
    } else {
        $estado = 'Reprobada';
    }

    return "
        <tr>
            <td>$clave</td>
            <td>$materia</td>
            <td>$periodo</td>
            <td>$formaEvaluacion</td>
            <td>$calificacion</td>
            <td>$estado</td>
        </tr>
    ";
}
