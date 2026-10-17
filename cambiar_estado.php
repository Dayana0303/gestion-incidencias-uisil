<?php
require_once 'includes/auth.php';
require_once 'config/conexion.php';

requerirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}
verificarCsrf();

$id = (int)($_POST['id'] ?? 0);
$estado = $_POST['estado'] ?? '';

if ($id > 0 && in_array($estado, $ESTADOS, true)) {
    $stmt = $conn->prepare('UPDATE incidencias SET estado = ? WHERE id = ?');
    $stmt->bind_param('si', $estado, $id);
    $stmt->execute();
    flash('success', "Estado de la incidencia #$id cambiado a \"$estado\".");
} else {
    flash('danger', 'Datos no válidos para cambiar el estado.');
}

// Conservar los filtros activos al volver
$volver = 'index.php' . (!empty($_POST['qs']) ? '?' . $_POST['qs'] : '');
header('Location: ' . $volver);
exit;
