<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nombre del estudiante (se muestra en el pie de página)
const NOMBRE_ESTUDIANTE = 'Dayana Murillo Solano';

function e($valor): string {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function estaLogueado(): bool {
    return isset($_SESSION['usuario_id']);
}

function requerirLogin(): void {
    if (!estaLogueado()) {
        $_SESSION['flash'] = ['warning', 'Debe iniciar sesión para realizar esta acción.'];
        header('Location: login.php');
        exit;
    }
}

function csrfToken(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function verificarCsrf(): void {
    $token = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $token)) {
        http_response_code(400);
        die('Solicitud no válida (token CSRF).');
    }
}

function flash(string $tipo, string $mensaje): void {
    $_SESSION['flash'] = [$tipo, $mensaje];
}