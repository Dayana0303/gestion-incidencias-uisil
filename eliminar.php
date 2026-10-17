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

if ($id > 0) {
    $stmt = $conn->prepare('DELETE FROM incidencias WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    flash('success', "Incidencia #$id eliminada.");
} else {
    flash('danger', 'Incidencia no válida.');
}

header('Location: index.php');
exit;
