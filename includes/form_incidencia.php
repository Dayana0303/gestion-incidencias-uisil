<?php
// Formulario compartido por registrar.php y editar.php
// Variables esperadas: $datos (array), $errores (array), $accion (string), $textoBoton (string)
?>
<form method="post" action="<?= e($accion) ?>" novalidate>
  <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label" for="reportante">Nombre de quien reporta *</label>
      <input type="text" class="form-control <?= isset($errores['reportante']) ? 'is-invalid' : '' ?>"
             id="reportante" name="reportante" maxlength="100" value="<?= e($datos['reportante']) ?>">
      <div class="invalid-feedback"><?= e($errores['reportante'] ?? '') ?></div>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="laboratorio">Laboratorio *</label>
      <select class="form-select <?= isset($errores['laboratorio']) ? 'is-invalid' : '' ?>" id="laboratorio" name="laboratorio">
        <option value="">-- Seleccione --</option>
        <?php foreach ($LABORATORIOS as $l): ?>
          <option value="<?= e($l) ?>" <?= $datos['laboratorio'] === $l ? 'selected' : '' ?>><?= e($l) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback"><?= e($errores['laboratorio'] ?? '') ?></div>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="equipo">Equipo afectado *</label>
      <input type="text" class="form-control <?= isset($errores['equipo']) ? 'is-invalid' : '' ?>"
             id="equipo" name="equipo" maxlength="100" placeholder="Ej: PC-05, proyector, router"
             value="<?= e($datos['equipo']) ?>">
      <div class="invalid-feedback"><?= e($errores['equipo'] ?? '') ?></div>
    </div>
    <div class="col-md-6">
      <label class="form-label" for="prioridad">Prioridad *</label>
      <select class="form-select <?= isset($errores['prioridad']) ? 'is-invalid' : '' ?>" id="prioridad" name="prioridad">
        <?php foreach ($PRIORIDADES as $p): ?>
          <option value="<?= e($p) ?>" <?= $datos['prioridad'] === $p ? 'selected' : '' ?>><?= e($p) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback"><?= e($errores['prioridad'] ?? '') ?></div>
    </div>
    <div class="col-12">
      <label class="form-label" for="descripcion">Descripción del problema *</label>
      <textarea class="form-control <?= isset($errores['descripcion']) ? 'is-invalid' : '' ?>"
                id="descripcion" name="descripcion" rows="4"><?= e($datos['descripcion']) ?></textarea>
      <div class="invalid-feedback"><?= e($errores['descripcion'] ?? '') ?></div>
    </div>
  </div>
  <div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-uisil"><?= e($textoBoton) ?></button>
    <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
