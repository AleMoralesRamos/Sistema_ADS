CREATE TABLE horarios
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    dia VARCHAR(20) NOT NULL,
    ini TIME NOT NULL,
    fin TIME NOT NULL,
    materia VARCHAR(100) NOT NULL,
    profesor VARCHAR(100)
);

CREATE TABLE calendario_eventos 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    evento VARCHAR(255) NOT NULL,
    tipo VARCHAR(50) 
);

CREATE TABLE mensajes 
(
    id INT AUTO_INCREMENT PRIMARY KEY,
    remitente_nombre VARCHAR(100),
    destinatario_tipo VARCHAR(50),
    asunto VARCHAR(150),
    mensaje TEXT,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP
);

--PRUEBAS
INSERT INTO horarios (dia, hora_inicio, hora_fin, materia, profesor) VALUES 
('Lunes', '08:00', '09:00', 'Matemáticas', 'Prof. Jirafales'),
('Lunes', '09:00', '10:00', 'Historia', 'Prof. X');

INSERT INTO calendario_eventos (fecha, evento, tipo) VALUES 
('2024-05-01', 'Día del Trabajo', 'Festivo'),
('2024-05-15', 'Día del Maestro', 'Festivo');