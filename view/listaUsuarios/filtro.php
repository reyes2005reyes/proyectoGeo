<?php
// Partial de filas para el filtro de usuarios.
if (!isset($usuariosArray) || !is_array($usuariosArray)) {
    $usuariosArray = [];
}

if (!empty($usuariosArray)) {
    foreach ($usuariosArray as $usuario) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($usuario['id_usuario']) . '</td>';
        echo '<td>' . htmlspecialchars($usuario['nombre_tipo_documento']) . '</td>';
        echo '<td>' . htmlspecialchars($usuario['numero_documento']) . '</td>';
        echo '<td>' . htmlspecialchars(trim(($usuario['primer_nombre'] ?? '') . ' ' . ($usuario['segundo_nombre'] ?? ''))) . '</td>';
        echo '<td>' . htmlspecialchars(trim(($usuario['primer_apellido'] ?? '') . ' ' . ($usuario['segundo_apellido'] ?? ''))) . '</td>';
        echo '<td>' . htmlspecialchars($usuario['telefono'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($usuario['nombre_rol'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($usuario['nombre_estado_usuario'] ?? '') . '</td>';
        echo '<td class="text-end">';
        echo '<div class="btn-group" role="group" aria-label="Acciones usuario">';
        echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?modulo=listaUsuarios&controlador=listaUsuarios&funcion=editar&id_usuario=' . urlencode($usuario['id_usuario']) . '" class="btn btn-sm btn-primary">Actualizar datos</a>';
        echo '<a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '?modulo=listaUsuarios&controlador=listaUsuarios&funcion=cambiarEstado&id_usuario=' . urlencode($usuario['id_usuario']) . '" class="btn btn-sm btn-warning">Cambiar estado</a>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr>';
    echo '<td colspan="9" class="text-center py-4">No se encontraron usuarios con los criterios seleccionados.</td>';
    echo '</tr>';
}



    



?>