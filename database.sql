-- Sistema de Gestión de Incidencias Tecnológicas - UISIL
-- Importar desde phpMyAdmin (pestaña "Importar") o con: mysql -u root < database.sql

CREATE DATABASE IF NOT EXISTS incidencias_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE incidencias_db;

DROP TABLE IF EXISTS incidencias;
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  nombre VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE incidencias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reportante VARCHAR(100) NOT NULL,
  laboratorio VARCHAR(50) NOT NULL,
  equipo VARCHAR(100) NOT NULL,
  descripcion TEXT NOT NULL,
  prioridad ENUM('Baja','Media','Alta') NOT NULL DEFAULT 'Media',
  estado ENUM('Pendiente','En proceso','Resuelta') NOT NULL DEFAULT 'Pendiente',
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_laboratorio (laboratorio),
  INDEX idx_estado (estado),
  INDEX idx_prioridad (prioridad)
) ENGINE=InnoDB;

-- Usuario administrador: admin / admin123 (contraseña guardada con password_hash)
INSERT INTO usuarios (usuario, nombre, password_hash) VALUES
('admin', 'Administrador', '$2y$10$yIVpr3QdBcCJ9rmm4Zp57evg8FBm4ArPZ/k4ASxTAUKp8jiEWDAWy');

-- Datos de ejemplo
INSERT INTO incidencias (reportante, laboratorio, equipo, descripcion, prioridad, estado) VALUES
('María Rojas', 'Laboratorio 1', 'PC-05', 'La computadora no enciende.', 'Alta', 'Pendiente'),
('Carlos Vargas', 'Laboratorio 2', 'Teclado PC-12', 'Varias teclas no responden.', 'Baja', 'En proceso'),
('Ana Solís', 'Laboratorio 3', 'Proyector', 'El proyector muestra la imagen con manchas.', 'Media', 'Pendiente'),
('Luis Mora', 'Laboratorio 1', 'Router', 'Conexión a Internet interrumpida.', 'Alta', 'Resuelta');
