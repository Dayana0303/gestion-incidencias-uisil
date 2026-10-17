<?php
require_once 'includes/auth.php';
require_once 'config/conexion.php';

// ---- Filtros (GET) ----
$fLab  = $_GET['laboratorio'] ?? '';
$fEst  = $_GET['estado'] ?? '';
$fPri  = $_GET['prioridad'] ?? '';

$where = [];
$tipos = '';
$params = [];

if (in_array($fLab, $LABORATORIOS, true)) { $where[] = 'laboratorio = ?'; $tipos .= 's'; $params[] = $fLab; } else { $fLab = ''; }
if (in_array($fEst, $ESTADOS, true))      { $where[] = 'estado = ?';      $tipos .= 's'; $params[] = $fEst; } else { $fEst = ''; }
if (in_array($fPri, $PRIORIDADES, true))  { $where[] = 'prioridad = ?';   $tipos .= 's'; $params[] = $fPri; } else { $fPri = ''; }

$sql = 'SELECT * FROM incidencias';
if ($where) { $sql .= ' WHERE ' . implode(' AND ', $where); }
$sql .= ' ORDER BY fecha_registro DESC, id DESC';

$stmt = $conn->prepare($sql);
if ($params) { $stmt->bind_param($tipos, ...$params); }
$stmt->execute();
$incidencias = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$qs = http_build_query(array_filter(['laboratorio' => $fLab, 'estado' => $fEst, 'prioridad' => $fPri]));

function badgePrioridad(string $p): string {
    return ['Alta' => 'danger', 'Media' => 'warning text-dark', 'Baja' => 'secondary'][$p] ?? 'secondary';
}
function badgeEstado(string $s): string {
    return ['Pendiente' => 'danger', 'En proceso' => 'primary', 'Resuelta' => 'success'][$s] ?? 'secondary';
}

$titulo = 'Incidencias';
require 'includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 mb-0">Listado de incidencias</h1>
  <a href="registrar.php" class="btn btn-uisil">+ Nueva incidencia</a>
</div>

<!-- Filtros -->
<form method="get" action="index.php" class="card card-body shadow-sm mb-3">
  <div class="row g-2 align-items-end">
    <div class="col-md-3">
      <label class="form-label small mb-1">Laboratorio</label>
      <select name="laboratorio" class="form-select">
        <option value="">Todos</option>
        <?php foreach ($LABORATORIOS as $l): ?>
          <option value="<?= e($l) ?>" <?= $fLab === $l ? 'selected' : '' ?>><?= e($l) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label small mb-1">Estado</label>
      <select name="estado" class="form-select">
        <option value="">Todos</option>
        <?php foreach ($ESTADOS as $s): ?>
          <option value="<?= e($s) ?>" <?= $fEst === $s ? 'selected' : '' ?>><?= e($s) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label small mb-1">Prioridad</label>
      <select name="prioridad" class="form-select">
        <option value="">Todas</option>
        <?php foreach ($PRIORIDADES as $p): ?>
          <option value="<?= e($p) ?>" <?= $fPri === $p ? 'selected' : '' ?>><?= e($p) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button class="btn btn-uisil flex-fill" type="submit">Filtrar</button>
      <a class="btn btn-outline-secondary flex-fill" href="index.php">Limpiar</a>
    </div>
  </div>
</form>

<p class="text-muted small"><?= count($incidencias) ?> incidencia(s) encontrada(s).</p>

<div class="table-responsive shadow-sm">
  <table class="table table-hover table-striped align-middle bg-white mb-0">
    <thead class="table-uisil">
      <tr>
        <th>#</th>
        <th>Fecha de registro</th>
        <th>Reporta</th>
        <th>Laboratorio</th>
        <th>Equipo</th>
        <th>Descripción</th>
        <th>Prioridad</th>
        <th>Estado</th>
        <?php if (estaLogueado()): ?><th class="text-end">Acciones</th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
    <?php if (!$incidencias): ?>
      <tr><td colspan="<?= estaLogueado() ? 9 : 8 ?>" class="text-center text-muted py-4">No hay incidencias para mostrar.</td></tr>
    <?php endif; ?>
    <?php foreach ($incidencias as $i): ?>
      <tr>
        <td><?= (int)$i['id'] ?></td>
        <td class="text-nowrap"><?= e(date('d/m/Y H:i', strtotime($i['fecha_registro']))) ?></td>
        <td><?= e($i['reportante']) ?></td>
        <td><?= e($i['laboratorio']) ?></td>
        <td><?= e($i['equipo']) ?></td>
        <td class="desc-col"><?= nl2br(e($i['descripcion'])) ?></td>
        <td><span class="badge bg-<?= badgePrioridad($i['prioridad']) ?>"><?= e($i['prioridad']) ?></span></td>
        <td>
          <?php if (estaLogueado()): ?>
            <form method="post" action="cambiar_estado.php" class="m-0">
              <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
              <input type="hidden" name="id" value="<?= (int)$i['id'] ?>">
              <input type="hidden" name="qs" value="<?= e($qs) ?>">
              <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php foreach ($ESTADOS as $s): ?>
                  <option value="<?= e($s) ?>" <?= $i['estado'] === $s ? 'selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          <?php else: ?>
            <span class="badge bg-<?= badgeEstado($i['estado']) ?>"><?= e($i['estado']) ?></span>
          <?php endif; ?>
        </td>
        <?php if (estaLogueado()): ?>
        <td class="text-end text-nowrap">
          <a href="editar.php?id=<?= (int)$i['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
          <form method="post" action="eliminar.php" class="d-inline"
                onsubmit="return confirm('¿Seguro que desea eliminar la incidencia #<?= (int)$i['id'] ?>? Esta acción no se puede deshacer.');">
            <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
            <input type="hidden" name="id" value="<?= (int)$i['id'] ?>">
            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
          </form>
        </td>
        <?php endif; ?>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php if (!estaLogueado()): ?>
  <p class="text-muted small mt-3">Para editar, eliminar o cambiar el estado de una incidencia, <a href="login.php">inicie sesión</a>.</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
