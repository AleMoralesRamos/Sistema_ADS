-- ============================================
-- SISTEMA ESCOLAR - ESTRUCTURA DE BASE DE DATOS
-- ============================================

-- Eliminar tablas si existen (opcional, quita el comentario si quieres empezar fresco)
DROP TABLE IF EXISTS mensajes;
DROP TABLE IF EXISTS calendario_eventos;
DROP TABLE IF EXISTS horarios;

-- Tabla de horarios
CREATE TABLE IF NOT EXISTS horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dia VARCHAR(20) NOT NULL,
    ini TIME NOT NULL,
    fin TIME NOT NULL,
    materia VARCHAR(100) NOT NULL,
    profesor VARCHAR(100)
);

-- Tabla de eventos del calendario
CREATE TABLE IF NOT EXISTS calendario_eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    evento VARCHAR(255) NOT NULL,
    tipo VARCHAR(50) 
);

-- Tabla de mensajes
CREATE TABLE IF NOT EXISTS mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    remitente_nombre VARCHAR(100),
    destinatario_tipo VARCHAR(50),
    asunto VARCHAR(150),
    mensaje TEXT,
    fecha_envio DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- DATOS DE PRUEBA
-- ============================================

-- Horarios de ejemplo
INSERT INTO horarios (dia, ini, fin, materia, profesor) VALUES 
('Lunes', '08:00:00', '09:00:00', 'Matemáticas', 'Prof. Jirafales'),
('Lunes', '09:00:00', '10:00:00', 'Historia', 'Prof. X'),
('Martes', '10:00:00', '11:00:00', 'Español', 'Prof. López'),
('Miércoles', '08:00:00', '09:30:00', 'Ciencias', 'Prof. Curie');

-- Eventos de ejemplo
INSERT INTO calendario_eventos (fecha, evento, tipo) VALUES 
(CURDATE(), 'Día del Trabajo', 'Festivo'),
(DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'Reunión de Padres', 'Reunión'),
(DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'Entrega de Calificaciones', 'Académico'),
(DATE_ADD(CURDATE(), INTERVAL 14 DAY), 'Feria de Ciencias', 'Evento');

-- Mensaje de ejemplo
INSERT INTO mensajes (remitente_nombre, destinatario_tipo, asunto, mensaje) VALUES 
('Juan Pérez', 'Profesor', 'Progreso Académico', 'Buen día, quisiera información sobre el progreso de mi hijo.');