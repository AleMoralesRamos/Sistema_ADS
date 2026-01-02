<?php

require('conexion.php');
require('fila.php');

$materiasPorNivel = [];

/* Obtener materias */
$sql = "SELECT * FROM materias ORDER BY nivel, semestre, clave";
$stmt = $pdo->query($sql);
$materias = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Agrupar por nivel y semestre */
foreach ($materias as $fila) {
    $nivel = $fila['nivel'];
    $semestre = $fila['semestre'];

    $materiasPorNivel[$nivel][$semestre][] = $fila;
}

/* Mostrar por nivel */
foreach ($materiasPorNivel as $nivel => $semestres) {

    // === PROMEDIO GENERAL POR NIVEL ===
    $sumaNivel = 0;
    $contadorNivel = 0;

    foreach ($semestres as $materias) {
        foreach ($materias as $fila) {
            if (!is_null($fila['calificacion'])) {
                $sumaNivel += $fila['calificacion'];
                $contadorNivel++;
            }
        }
    }

    $promedioNivel = $contadorNivel > 0
        ? number_format($sumaNivel / $contadorNivel, 2)
        : 'N/A';

    //echo "<section class='nivel'>";
    //echo "<h1>Nivel: $nivel</h1>";
    echo "<div class='nivel'>
            <h2>Nivel Educativo: $nivel</h2>
          </div>";

    echo "<div class='promedio-general'>
            <h2>Promedio General del Nivel: $promedioNivel</h2>
          </div>";

    // === TABLAS POR SEMESTRE (IGUAL QUE ANTES) ===
    foreach ($semestres as $semestre => $materias) {

        echo "<div class='semestre'>";
        echo "<h2>Semestre $semestre</h2>";
        echo "<div class='table-wrapper'><table>";

        echo "<thead>
                <tr>
                    <th>Clave</th>
                    <th>Materia</th>
                    <th>Periodo</th>
                    <th>Forma Eval.</th>
                    <th>Calificación</th>
                    <th>Estado</th>
                    <th>Promedio Semestre</th>
                </tr>
              </thead><tbody>";

        $sumaSemestre = 0;
        $contadorSemestre = 0;

        foreach ($materias as $fila) {
            echo cntRenglon($fila);

            if (!is_null($fila['calificacion'])) {
                $sumaSemestre += $fila['calificacion'];
                $contadorSemestre++;
            }
        }

        $promedioSemestre = $contadorSemestre > 0
            ? number_format($sumaSemestre / $contadorSemestre, 2)
            : 'N/A';

        echo "<tr>
                <td colspan='6' style='text-align:right;'>Promedio Semestre:</td>
                <td>$promedioSemestre</td>
              </tr>";

        echo "</tbody></table></div></div>";
    }

    echo "</section>";
}
