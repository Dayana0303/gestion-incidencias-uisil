<?php
// Valida y limpia los datos del formulario de incidencias.
// Devuelve [$datos, $errores]
function validarIncidencia(array $post, array $LABORATORIOS, array $PRIORIDADES): array {
    $datos = [
        'reportante'  => trim($post['reportante'] ?? ''),
        'laboratorio' => trim($post['laboratorio'] ?? ''),
        'equipo'      => trim($post['equipo'] ?? ''),
        'descripcion' => trim($post['descripcion'] ?? ''),
        'prioridad'   => trim($post['prioridad'] ?? ''),
    ];
    $errores = [];

    if ($datos['reportante'] === '') {
        $errores['reportante'] = 'El nombre es obligatorio.';
    } elseif (mb_strlen($datos['reportante']) > 100) {
        $errores['reportante'] = 'Máximo 100 caracteres.';
    }
    if ($datos['laboratorio'] === '' || !in_array($datos['laboratorio'], $LABORATORIOS, true)) {
        $errores['laboratorio'] = 'Seleccione un laboratorio.';
    }
    if ($datos['equipo'] === '') {
        $errores['equipo'] = 'El equipo afectado es obligatorio.';
    } elseif (mb_strlen($datos['equipo']) > 100) {
        $errores['equipo'] = 'Máximo 100 caracteres.';
    }
    if ($datos['descripcion'] === '') {
        $errores['descripcion'] = 'La descripción es obligatoria.';
    }
    if (!in_array($datos['prioridad'], $PRIORIDADES, true)) {
        $errores['prioridad'] = 'Seleccione una prioridad válida.';
    }
    return [$datos, $errores];
}
