<?php
// Configuración de conexión (valores por defecto de XAMPP)
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'incidencias_db';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    die('No se pudo conectar a la base de datos. Verifique que MySQL esté activo y que haya importado database.sql.');
}

// Catálogos usados en formularios y filtros
$LABORATORIOS = ['Laboratorio 1', 'Laboratorio 2', 'Laboratorio 3', 'Laboratorio 4'];
$PRIORIDADES  = ['Baja', 'Media', 'Alta'];
$ESTADOS      = ['Pendiente', 'En proceso', 'Resuelta'];
