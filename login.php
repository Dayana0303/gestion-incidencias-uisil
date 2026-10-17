<?php
require_once 'includes/auth.php';
require_once 'config/conexion.php';

if (estaLogueado()) {
    header('Location: index.php');
    exit;
}

$error = '';
$usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificarCsrf();
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usuario === '' || $password === '') {
        $error = 'Debe ingresar usuario y contraseña.';
    } else {
        $stmt = $conn->prepare('SELECT id, nombre, password_hash FROM usuarios WHERE usuario = ?');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $u = $stmt->get_result()->fetch_assoc();

        if ($u && password_verify($password, $u['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $u['id'];
            $_SESSION['usuario_nombre'] = $u['nombre'];
            flash('success', 'Bienvenido, ' . $u['nombre'] . '.');
            header('Location: index.php');
            exit;
        }
        $error = 'Usuario o contraseña incorrectos.';
    }
}

$titulo = 'Iniciar sesión';
require 'includes/header.php';
?>
<div class="row justify-content-center">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header card-header-uisil">Acceso administrativo</div>
      <div class="card-body">
        <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
        <form method="post" action="login.php" novalidate>
          <input type="hidden" name="csrf" value="<?= e(csrfToken()) ?>">
          <div class="mb-3">
            <label class="form-label" for="usuario">Usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario" value="<?= e($usuario) ?>" autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label" for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password">
          </div>
          <button type="submit" class="btn btn-uisil w-100">Ingresar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require 'includes/footer.php'; ?>
