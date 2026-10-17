<?php require_once __DIR__ . '/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo ?? 'Gestión de Incidencias') ?> | UISIL</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark navbar-uisil mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">🛠️ Incidencias Tecnológicas</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Incidencias</a></li>
        <li class="nav-item"><a class="nav-link" href="registrar.php">Registrar incidencia</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2 text-white">
        <?php if (estaLogueado()): ?>
          <span class="small">👤 <?= e($_SESSION['usuario_nombre']) ?></span>
          <a class="btn btn-sm btn-light" href="logout.php">Cerrar sesión</a>
        <?php else: ?>
          <a class="btn btn-sm btn-light" href="login.php">Acceso administrativo</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main class="container">
<?php if (!empty($_SESSION['flash'])): [$tipoFlash, $msgFlash] = $_SESSION['flash']; unset($_SESSION['flash']); ?>
  <div class="alert alert-<?= e($tipoFlash) ?> alert-dismissible fade show" role="alert">
    <?= e($msgFlash) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>
