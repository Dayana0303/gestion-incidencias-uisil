<?php
require_once 'includes/auth.php';
require_once 'config/conexion.php';
require_once 'includes/validar.php';

$datos = ['reportante' => '', 'laboratorio' => '', 'equipo' => '', 'descripcion' => '', 'prioridad' => 'Media'];
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    [$datos, $errores] = validarIncidencia($_POST, $LABORATORIOS, $PRIORIDADES);

    if (!$errores) {
        $stmt = $conn->prepare(
            'INSERT INTO incidencias (reportante, laboratorio, equipo, descripcion, prioridad) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('sssss', $datos['reportante'], $datos['laboratorio'], $datos['equipo'], $datos['descripcion'], $datos['prioridad']);
        $stmt->execute();
        flash('success', 'Incidencia registrada correctamente.');
        header('Location: index.php');
        exit;
    }
}

$titulo = 'Registrar incidencia';
$accion = 'registrar.php';
$textoBoton = 'Registrar incidencia';
require 'includes/header.php';
?>
<div class="card shadow-sm">
  <div class="card-header card-header-uisil">Registrar nueva incidencia</div>
  <div class="card-body">
    <?php require 'includes/form_incidencia.php'; ?>
  </div>
</div>
<?php require 'includes/footer.php'; ?>
