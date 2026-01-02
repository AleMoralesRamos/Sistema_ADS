-- Tabla de materias para sistema escolar
CREATE TABLE IF NOT EXISTS materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nivel ENUM('Kinder','Primaria','Secundaria') NOT NULL,
    semestre INT NOT NULL,
    clave VARCHAR(10) NOT NULL,
    materia VARCHAR(150) NOT NULL,
    calificacion INT DEFAULT NULL,
    periodo VARCHAR(10) DEFAULT NULL,
    forma_evaluacion VARCHAR(10) DEFAULT NULL,
    estado VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- Datos de ejemplo para materias
INSERT INTO materias (nivel, semestre, clave, materia, calificacion, periodo, forma_evaluacion, estado) VALUES
('Kinder', 1, 'K001', 'Desarrollo Motriz', 9, '23/1', 'ORD', 'Aprobada'),
('Kinder', 1, 'K002', 'Lenguaje Inicial', 10, '23/1', 'ORD', 'Aprobada'),
('Kinder', 1, 'K003', 'Expresión Artística', 8, '23/1', 'ORD', 'Aprobada'),
('Kinder', 2, 'K004', 'Pensamiento Matemático', 7, '23/2', 'ORD', 'Aprobada'),
('Kinder', 2, 'K005', 'Convivencia Social', 8, '26/1', 'ORD', 'Sin cursar'),
('Primaria', 1, 'P101', 'Español I', 8, '24/1', 'ORD', 'Aprobada'),
('Primaria', 1, 'P102', 'Matemáticas I', 7, '24/1', 'ORD', 'Aprobada'),
('Primaria', 2, 'P103', 'Ciencias Naturales', 9, '24/2', 'ORD', 'Aprobada'),
('Primaria', 2, 'P104', 'Historia', 6, '24/2', 'REC', 'Aprobada'),
('Primaria', 3, 'P105', 'Geografía', NULL, NULL, NULL, 'Sin cursar'),
('Secundaria', 1, 'S201', 'Matemáticas Avanzadas', 6, '25/1', 'ORD', 'Aprobada'),
('Secundaria', 1, 'S202', 'Física', 7, '25/1', 'ORD', 'Aprobada'),
('Secundaria', 2, 'S203', 'Química', 5, '25/2', 'ORD', 'Reprobada'),
('Secundaria', 2, 'S204', 'Tecnología', 8, '25/2', 'ORD', 'Aprobada'),
('Secundaria', 3, 'S205', 'Formación Cívica', 5, '26/1', 'ORD', 'Sin cursar');