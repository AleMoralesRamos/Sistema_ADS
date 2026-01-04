<?php
require_once('conexion.php'); // Usamos require_once para asegurar conexión
require_once('fila.php');

$materiasPorNivel = [];

// Usamos la boleta que viene de calif.php (o de la sesión)
$boleta_actual = $_SESSION['boleta'];

/* CONSULTA CORREGIDA:
   Traemos todas las materias y las unimos con el kardex 
   PERO solo para el alumno logueado.
*/
$sql = "SELECT m.clave, m.materia, m.nivel, m.semestre, 
               k.calificacion, k.periodo, k.forma_evaluacion, k.estado 
        FROM materias m
        LEFT JOIN kardex k ON m.clave = k.clave AND k.boleta = '$boleta_actual'
        ORDER BY m.nivel, m.semestre, m.clave";

$result = $conn->query($sql);

if ($result) {
    /* Agrupar por nivel y semestre */
    while ($fila = $result->fetch_assoc()) {
        $nivel = $fila['nivel'];
        $semestre = $fila['semestre'];
        
        // Guardamos la fila en el array multidimensional
        $materiasPorNivel[$nivel][$semestre][] = $fila;
    }
}

/* Mostrar por nivel */
if (empty($materiasPorNivel)) {
    echo "<p>No se encontraron materias registradas.</p>";
} else {
    foreach ($materiasPorNivel as $nivel => $semestres) {

        // === PROMEDIO GENERAL POR NIVEL ===
        $sumaNivel = 0;
        $contadorNivel = 0;

        foreach ($semestres as $materias) {
            foreach ($materias as $fila) {
                // Solo sumamos si tiene calificación y no es nula
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

        // === TABLAS POR SEMESTRE ===
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
                // Llamamos a la función de fila.php para dibujar el renglón
                echo cntRenglon($fila);

                if (isset($fila['calificacion']) && $fila['calificacion'] !== null && $fila['calificacion'] !== '') {
                    $sumaSemestre += $fila['calificacion'];
                    $contadorSemestre++;
                }
            }

            $promedioSemestre = $contadorSemestre > 0
                ? number_format($sumaSemestre / $contadorSemestre, 2)
                : '-';

            // Fila de promedio del semestre
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