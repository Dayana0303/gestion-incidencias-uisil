<?php
require_once 'includes/auth.php';
require_once 'config/conexion.php';
require_once 'includes/validar.php';

requerirLogin();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $conn->prepare('SELECT * FROM incidencias WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$incidencia = $stmt->get_result()->fetch_assoc();

if (!$incidencia) {
    flash('danger', 'La incidencia no existe.');
    header('Location: index.php');
    exit;
}

$datos = $incidencia;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    [$datos, $errores] = validarIncidencia($_POST, $LABORATORIOS, $PRIORIDADES);

    if (!$errores) {
        $stmt = $conn->prepare(
            'UPDATE incidencias SET reportante = ?, laboratorio = ?, equipo = ?, descripcion = ?, prioridad = ? WHERE id = ?'
        );
        $stmt->bind_param('sssssi', $datos['reportante'], $datos['laboratorio'], $datos['equipo'], $datos['descripcion'], $datos['prioridad'], $id);
        $stmt->execute();
        flash('success', 'Incidencia actualizada correctamente.');
        header('Location: index.php');
        exit;
    }
}

$titulo = 'Editar incidencia';
$accion = 'editar.php?id=' . $id;
$textoBoton = 'Guardar cambios';
require 'includes/header.php';
?>
<div class="card shadow-sm">
  <div class="card-header card-header-uisil">Editar incidencia #<?= $id ?></div>
  <div class="card-body">
    <?php require 'includes/form_incidencia.php'; ?>
  </div>
</div>
<?php require 'includes/footer.php'; ?>
