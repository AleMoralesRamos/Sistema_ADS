<?php
require_once('conexion.php');
require_once('fila.php');

$materiasPorNivel = [];

$boleta_actual = $_SESSION['boleta'];

$sql = "SELECT m.clave, m.materia, m.nivel, m.semestre, 
               k.calificacion, k.periodo, k.forma_evaluacion, k.estado 
        FROM materias m
        LEFT JOIN kardex k ON m.clave = k.clave AND k.boleta = '$boleta_actual'
        ORDER BY m.nivel, m.semestre, m.clave";

$result = $conn->query($sql);

if ($result) {
    while ($fila = $result->fetch_assoc()) {
        $nivel = $fila['nivel'];
        $semestre = $fila['semestre'];
        
        $materiasPorNivel[$nivel][$semestre][] = $fila;
    }
}

/* Mostrar por nivel */
if (empty($materiasPorNivel)) {
    echo "<p>No se encontraron materias registradas.</p>";
} else {
    foreach ($materiasPorNivel as $nivel => $semestres) {

        //Promedio general
        $sumaNivel = 0;
        $contadorNivel = 0;

        foreach ($semestres as $materias) {
            foreach ($materias as $fila) {
                if (isset($fila['calificacion']) && $fila['calificacion'] !== null && $fila['calificacion'] !== '') {
                    $sumaNivel += $fila['calificacion'];
                    $contadorNivel++;
                }
            }
        }

        $promedioNivel = $contadorNivel > 0
            ? number_format($sumaNivel / $contadorNivel, 2)
            : '-';

        echo "<div class='nivel'>
                <h2>🎓 Nivel Educativo: $nivel</h2>
              </div>";

        echo "<div class='promedio-general'>
                Promedio General del Nivel: <span>$promedioNivel</span>
              </div>";

        // Tabla por semestre
        foreach ($semestres as $semestre => $materias) {

            echo "<div class='semestre'>";
            echo "<h3>📅 Semestre $semestre</h3>";
            echo "<div class='table-wrapper'><table>";

            echo "<thead>
                    <tr>
                        <th>Clave</th>
                        <th>Materia</th>
                        <th>Periodo</th>
                        <th>Forma Eval.</th>
                        <th>Calificación</th>
                        <th>Estado</th>
                    </tr>
                  </thead><tbody>";

            $sumaSemestre = 0;
            $contadorSemestre = 0;

            foreach ($materias as $fila) {
                echo cntRenglon($fila);

                if (isset($fila['calificacion']) && $fila['calificacion'] !== null && $fila['calificacion'] !== '') {
                    $sumaSemestre += $fila['calificacion'];
                    $contadorSemestre++;
                }
            }

            $promedioSemestre = $contadorSemestre > 0
                ? number_format($sumaSemestre / $contadorSemestre, 2)
                : '-';

            echo "<tr style='background-color: #fafafa; font-weight: bold;'>
                    <td colspan='4' style='text-align:right;'>Promedio Semestre:</td>
                    <td colspan='2' style='color:rgb(0, 0, 0);'>$promedioSemestre</td>
                  </tr>";

            echo "</tbody></table></div></div>";
        }
        echo "<br>";
    }
}
?>