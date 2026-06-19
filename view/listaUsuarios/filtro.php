<?php
if (!isset($usuariosArray) || !is_array($usuariosArray)) {
    $usuariosArray = array();
}

if (!empty($usuariosArray)) {
    foreach ($usuariosArray as $usuario) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($usuario['id_usuario']) . '</td>';
        echo '<td>' . htmlspecialchars($usuario['nombre_tipo_documento']) . '</td>';
        echo '<td>' . htmlspecialchars($usuario['numero_documento']) . '</td>';
        echo '<td>' . htmlspecialchars(trim((isset($usuario['primer_nombre']) ? $usuario['primer_nombre'] : '') . ' ' . (isset($usuario['segundo_nombre']) ? $usuario['segundo_nombre'] : ''))) . '</td>';
        echo '<td>' . htmlspecialchars(trim((isset($usuario['primer_apellido']) ? $usuario['primer_apellido'] : '') . ' ' . (isset($usuario['segundo_apellido']) ? $usuario['segundo_apellido'] : ''))) . '</td>';
        echo '<td>' . htmlspecialchars(isset($usuario['telefono']) ? $usuario['telefono'] : '') . '</td>';
        echo '<td>' . htmlspecialchars(isset($usuario['nombre_rol']) ? $usuario['nombre_rol'] : '') . '</td>';
        echo '<td>' . htmlspecialchars(isset($usuario['nombre_estado_usuario']) ? $usuario['nombre_estado_usuario'] : '') . '</td>';
        echo '<td class="text-end">';
        echo '<div class="btn-group" role="group" aria-label="Acciones usuario">';
        echo '<button type="button" class="btn btn-sm btn-primary" onclick="abrirModalEditar(' . (int)$usuario['id_usuario'] . ')">Actualizar datos</button>';
        echo '<button type="button" class="btn btn-sm btn-warning" onclick="abrirModalCambiarEstado(' . (int)$usuario['id_usuario'] . ')">Cambiar estado</button>';
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