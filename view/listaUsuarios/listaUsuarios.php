<?php
// La consulta se realiza en el modelo y el controlador pasa las variables
// Variables esperadas: $usuarios (array) y $numeroDocumento (string)
if (!isset($usuarios) || !is_array($usuarios)) {
    $usuarios = [];
}

if (!isset($numeroDocumento)) {
    $numeroDocumento = '';
}

function formatNombreCompleto($usuario)
{
    $partes = array_filter([
        $usuario['primer_nombre'] ?? '',
        $usuario['segundo_nombre'] ?? ''
    ]);
    return implode(' ', $partes);
}

function formatApellidoCompleto($usuario)
{
    $partes = array_filter([
        $usuario['primer_apellido'] ?? '',
        $usuario['segundo_apellido'] ?? ''
    ]);
    return implode(' ', $partes);
}

function formatEstado($estado)
{
    return stripos($estado, 'habilitado') !== false ? 'Habilitado' : 'Inhabilitado';
}
?>

<div id="listaUsuariosCard" class="card shadow-sm mt-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Lista de Usuarios</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?modulo=usuarios&controlador=usuarios&funcion=lista" class="row gy-3 gx-2 align-items-end">
            <div class="col-md-4">
                <label for="numero_documento" class="form-label">Ingrese número de cédula</label>
                <input
                    type="text"
                    name="numero_documento"
                    id="numero_documento"
                    class="form-control"
                    placeholder="Ingrese número de cédula"
                    value="<?php echo htmlspecialchars($numeroDocumento); ?>"
                >
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">Buscar</button>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted">Dejar vacío mostrará todos los usuarios.</span>
            </div>
        </form>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tipo documento</th>
                        <th>Número de identificación</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($usuarios) && count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre_tipo_documento']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['numero_documento']); ?></td>
                                <td><?php echo htmlspecialchars(formatNombreCompleto($usuario)); ?></td>
                                <td><?php echo htmlspecialchars(formatApellidoCompleto($usuario)); ?></td>
                                <td><?php echo htmlspecialchars($usuario['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre_rol']); ?></td>
                                <td><?php echo htmlspecialchars(formatEstado($usuario['nombre_estado_usuario'])); ?></td>
                                <td class="text-end">
                                    <div class="btn-group" role="group" aria-label="Acciones usuario">
                                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?modulo=listaUsuarios&controlador=listaUsuarios&funcion=editar&id_usuario=<?php echo urlencode($usuario['id_usuario']); ?>" class="btn btn-sm btn-primary">
                                            Actualizar datos
                                        </a>
                                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?modulo=listaUsuarios&controlador=listaUsuarios&funcion=cambiarEstado&id_usuario=<?php echo urlencode($usuario['id_usuario']); ?>" class="btn btn-sm btn-warning">
                                            Cambiar estado
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                No se encontraron usuarios con los criterios seleccionados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
        </div>

        <script>
            // Asegura que el listado quede visible al cargar la vista
            (function(){
                var el = document.getElementById('listaUsuariosCard');
                if(el){
                    // desplazar suavemente hasta el inicio del listado
                    el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })();
        </script>
