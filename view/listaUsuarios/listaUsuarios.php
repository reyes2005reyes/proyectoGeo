<?php
// La consulta se realiza en el modelo y el controlador pasa las variables
// Variables esperadas: $usuarios (array) y $numeroDocumento (string)
if (!isset($usuarios) || !is_array($usuarios)) {
    $usuarios = array();
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
    return stripos($estado, 'inhabilitado') !== false ? 'Inhabilitado' : 'Habilitado';
}
?>

<div id="listaUsuariosCard" class="card shadow-sm mt-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Lista de Usuarios</h4>
    </div>
    <div class="card-body">
        <div class="mb-3 row align-items-center">
            <div class="col-md-4">
                <input
                    type="search"
                    name="numero_documento"
                    id="filtro"
                    class="form-control"
                    placeholder="Buscar por número de documento"
                    value="<?php echo htmlspecialchars($numeroDocumento); ?>"
                    data-url="<?php echo getUrl('usuarios','usuarios','filtro',false,'ajax'); ?>"
                >
            </div>
        </div>

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
                <tbody id="usuariosFiltro">
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
                                        <button type="button" class="btn btn-sm btn-primary" onclick="abrirModalEditar(<?php echo $usuario['id_usuario']; ?>)">Actualizar datos</button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="abrirModalCambiarEstado(<?php echo $usuario['id_usuario']; ?>)">Cambiar estado</button>
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

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Actualizar Datos del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditarUsuario" method="POST" action="<?php echo getUrl('usuarios','usuarios','actualizarUsuario',false,'ajax'); ?>">
                <div class="modal-body">
                    <input type="hidden" id="idUsuario" name="id_usuario">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipoDocumento" class="form-label">Tipo de Documento</label>
                                <select id="tipoDocumento" name="id_tipo_documento" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <!-- Se llenará con JavaScript -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numeroDocumento" class="form-label">Número de Documento</label>
                                <input type="text" id="numeroDocumento" name="numero_documento" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="primerNombre" class="form-label">Primer Nombre</label>
                                <input type="text" id="primerNombre" name="primer_nombre" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="segundoNombre" class="form-label">Segundo Nombre</label>
                                <input type="text" id="segundoNombre" name="segundo_nombre" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="primerApellido" class="form-label">Primer Apellido</label>
                                <input type="text" id="primerApellido" name="primer_apellido" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="segundoApellido" class="form-label">Segundo Apellido</label>
                                <input type="text" id="segundoApellido" name="segundo_apellido" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" id="correo" name="correo" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" id="telefono" name="telefono" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección de Residencia</label>
                        <input type="text" id="direccion" name="direccion" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol</label>
                                <select id="rol" name="id_rol" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <!-- Se llenará con JavaScript -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contraseña (opcional)</label>
                                <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Dejar vacío para no cambiar">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Cambiar Estado -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1" role="dialog" aria-labelledby="modalCambiarEstadoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCambiarEstadoLabel">Cambiar Estado del Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCambiarEstado" method="POST" action="<?php echo getUrl('usuarios','usuarios','cambiarEstadoUsuario',false,'ajax'); ?>">
                <div class="modal-body">
                    <input type="hidden" id="idUsuarioCambiarEstado" name="id_usuario">
                    <p>¿Está seguro de que desea cambiar el estado de este usuario?</p>
                    <p id="infoEstadoActual" class="text-muted fw-bold"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Confirmar cambio</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables globales para almacenar datos
let tiposDocumento = [];
let roles = [];
let datosYaCargados = false;

// Cargar tipos de documento y roles al iniciar
function cargarDatos() {
    if (datosYaCargados) {
        return;
    }
    
    $.ajax({
        url: '<?php echo getUrl('usuarios','usuarios','obtenerTiposYRoles',false,'ajax'); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Datos cargados:', data);
            tiposDocumento = data.tiposDocumento || [];
            roles = data.roles || [];
            datosYaCargados = true;
            poblareSelects();
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar tipos de documento y roles:', status, error);
        }
    });
}

// Poblar los selects de tipo documento y rol
function poblareSelects() {
    const selectTipoDoc = document.getElementById('tipoDocumento');
    const selectRol = document.getElementById('rol');
    
    if (!selectTipoDoc || !selectRol) {
        console.warn('Selects no encontrados en el DOM');
        return;
    }
    
    // Limpiar opciones
    selectTipoDoc.innerHTML = '<option value="">Seleccione...</option>';
    selectRol.innerHTML = '<option value="">Seleccione...</option>';
    
    // Agregar tipos de documento
    tiposDocumento.forEach(tipo => {
        const option = document.createElement('option');
        option.value = tipo.id_tipo_documento;
        option.textContent = tipo.nombre_tipo_documento;
        selectTipoDoc.appendChild(option);
    });
    
    // Agregar roles
    roles.forEach(rol => {
        const option = document.createElement('option');
        option.value = rol.id_rol;
        option.textContent = rol.nombre_rol;
        selectRol.appendChild(option);
    });
    
    console.log('Selects poblados correctamente');
}

// Abrir modal de editar usuario
function abrirModalEditar(idUsuario) {
    // Asegurar que los datos estén cargados
    if (!datosYaCargados) {
        console.log('Esperando a que se carguen los datos...');
        // Esperar un poco a que se carguen los datos
        setTimeout(() => abrirModalEditar(idUsuario), 500);
        return;
    }
    
    $.ajax({
        url: '<?php echo getUrl('usuarios','usuarios','obtenerUsuarioJson',false,'ajax'); ?>',
        type: 'GET',
        data: { id_usuario: idUsuario },
        dataType: 'json',
        success: function(usuario) {
            console.log('Usuario cargado:', usuario);
            
            // Llenar el formulario
            document.getElementById('idUsuario').value = usuario.id_usuario;
            document.getElementById('numeroDocumento').value = usuario.numero_documento || '';
            document.getElementById('primerNombre').value = usuario.primer_nombre || '';
            document.getElementById('segundoNombre').value = usuario.segundo_nombre || '';
            document.getElementById('primerApellido').value = usuario.primer_apellido || '';
            document.getElementById('segundoApellido').value = usuario.segundo_apellido || '';
            document.getElementById('correo').value = usuario.correo || '';
            document.getElementById('telefono').value = usuario.telefono || '';
            document.getElementById('direccion').value = usuario.direccion || '';
            const contrasenaInput = document.getElementById('contrasena');
            if (contrasenaInput) {
                contrasenaInput.value = '';
            }
            
            // Establecer selects
            document.getElementById('tipoDocumento').value = usuario.id_tipo_documento || '';
            document.getElementById('rol').value = usuario.id_rol || '';
            
            const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
            modal.show();
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los datos del usuario:', status, error);
            alert('Error al cargar los datos del usuario');
        }
    });
}

// Abrir modal de cambiar estado
function abrirModalCambiarEstado(idUsuario) {
    $.ajax({
        url: '<?php echo getUrl('usuarios','usuarios','obtenerUsuarioJson',false,'ajax'); ?>',
        type: 'GET',
        data: { id_usuario: idUsuario },
        dataType: 'json',
        success: function(usuario) {
            document.getElementById('idUsuarioCambiarEstado').value = usuario.id_usuario;
            const estadoActual = usuario.nombre_estado_usuario;
            const nuevoEstado = estadoActual.toLowerCase().includes('inhabilitado') ? 'Habilitado' : 'Inhabilitado';
            document.getElementById('infoEstadoActual').innerHTML = `Estado actual: <strong>${estadoActual}</strong><br>Nuevo estado: <strong>${nuevoEstado}</strong>`;
            
            const modal = new bootstrap.Modal(document.getElementById('modalCambiarEstado'));
            modal.show();
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los datos del usuario:', status, error);
            alert('Error al cargar los datos del usuario');
        }
    });
}

// Enviar formulario de editar usuario
document.addEventListener('DOMContentLoaded', function() {
    const formEditarUsuario = document.getElementById('formEditarUsuario');
    if (formEditarUsuario) {
        formEditarUsuario.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $.ajax({
                url: this.action,
                type: 'POST',
                data: Object.fromEntries(formData),
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta:', response);
                    if(response.success) {
                        alert('Usuario actualizado correctamente');
                        bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario')).hide();
                        location.reload();
                    } else {
                        alert('Error: ' + (response.message || 'No se pudo actualizar el usuario'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                    alert('Error al actualizar el usuario');
                }
            });
        });
    }
    
    // Enviar formulario de cambiar estado
    const formCambiarEstado = document.getElementById('formCambiarEstado');
    if (formCambiarEstado) {
        formCambiarEstado.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            $.ajax({
                url: this.action,
                type: 'POST',
                data: Object.fromEntries(formData),
                dataType: 'json',
                success: function(response) {
    if(response.success) {
        bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
        // Refrescar la tabla usando el filtro que ya existe
        const busqueda = document.getElementById('filtro') ? document.getElementById('filtro').value : '';
        $.ajax({
            url: '<?php echo getUrl('usuarios','usuarios','filtro',false,'ajax'); ?>',
            type: 'GET',
            data: { buscar: busqueda },
            success: function(html) {
                document.getElementById('usuariosFiltro').innerHTML = html;
            }
        });
    } else {
        alert('Error: ' + (response.message || 'No se pudo cambiar el estado'));
    }
},
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                    alert('Error al cambiar el estado');
                }
            });
        });
    }
    
    // Cargar datos al iniciar la página
    cargarDatos();
});
</script>
