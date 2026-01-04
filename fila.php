<?php
function cntRenglon($datos) {
    $clave           = $datos['clave'] ?? '';
    $materia         = $datos['materia'] ?? '';
    $periodo         = $datos['periodo'] ?? '-';
    $formaEvaluacion = $datos['forma_evaluacion'] ?? '-';
    $calificacion    = $datos['calificacion']; // Puede ser null
    
    // Lógica del estado visual
    $estado = 'Sin cursar';
    $color_estado = '#999'; // Gris por defecto
    $fondo_calif = 'transparent';

    if ($calificacion !== null && $calificacion !== '') {
        if ($calificacion >= 6) {
            $estado = 'Aprobada';
            $color_estado = 'green';
            $fondo_calif = '#e8f5e9'; // Verde clarito
        } else {
            $estado = 'Reprobada';
            $color_estado = 'red';
            $fondo_calif = '#ffebee'; // Rojo clarito
        }
        $calif_display = $calificacion;
    } else {
        $calif_display = '-';
    }

    return "
        <tr>
            <td>$clave</td>
            <td>$materia</td>
            <td>$periodo</td>
            <td>$formaEvaluacion</td>
            <td style='background-color: $fondo_calif; font-weight: bold; text-align: center;'>$calif_display</td>
            <td style='color: $color_estado; font-weight: bold;'>$estado</td>
        </tr>
    ";
}
?>