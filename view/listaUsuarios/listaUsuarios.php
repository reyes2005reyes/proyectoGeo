<?php
// Variables esperadas: $usuarios (array) y $numeroDocumento (string)
if (!isset($usuarios) || !is_array($usuarios)) {
    $usuarios = array();
}

if (!isset($numeroDocumento)) {
    $numeroDocumento = '';
}

function formatNombreCompleto($usuario)
{
    $partes = array_filter(array(
        isset($usuario['primer_nombre'])  ? $usuario['primer_nombre']  : '',
        isset($usuario['segundo_nombre']) ? $usuario['segundo_nombre'] : ''
    ));
    return implode(' ', $partes);
}

function formatApellidoCompleto($usuario)
{
    $partes = array_filter(array(
        isset($usuario['primer_apellido'])  ? $usuario['primer_apellido']  : '',
        isset($usuario['segundo_apellido']) ? $usuario['segundo_apellido'] : ''
    ));
    return implode(' ', $partes);
}

function formatEstado($estado)
{
    return stripos($estado, 'inhabilitado') !== false ? 'Inhabilitado' : 'Habilitado';
}
?>

<!-- Flash mensajes -->
<div id="flashContainer" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;max-width:400px;"></div>

<link rel="stylesheet" href="/proyectoGeo/web/assets/css/listaUsuarios.css">

<div id="listaUsuariosCard" class="card shadow-sm mt-0">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Lista de Usuarios</h4>
    </div>
    <div class="card-body">
        <div class="mb-3 row align-items-center">
            <div class="col-md-4">
                <input
                    type="number"
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
                                    <?php 
                                    $idRolSesionVista = isset($_SESSION['id_rol']) ? (int)$_SESSION['id_rol'] : 0;
                                    $idRolObjetivoVista = isset($usuario['id_rol']) ? (int)$usuario['id_rol'] : 0;
                                    $esAdminVista = ($idRolSesionVista === 1);
                                    $puedeEditar = $esAdminVista || ($idRolSesionVista === 2 && $idRolObjetivoVista === 3);
                                    $puedeCambiarEstado = $esAdminVista;
                                    ?>
                                    <div class="btn-group" role="group" aria-label="Acciones usuario">
                                        <?php if ($puedeEditar): ?>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="abrirModalEditar(<?php echo $usuario['id_usuario']; ?>)">Actualizar datos</button>
                                        <?php endif; ?>
                                        <?php if ($puedeCambiarEstado): ?>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="abrirModalCambiarEstado(<?php echo $usuario['id_usuario']; ?>)">Cambiar estado</button>
                                        <?php endif; ?>
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
                                <label for="tipoDocumento" class="form-label">Tipo de Documento <span class="text-danger">*</span></label>
                                <select id="tipoDocumento" name="id_tipo_documento" class="form-control" required>
                                    <option value="">Seleccione...</option>
                                    <!-- Se llenará con JavaScript -->
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="numeroDocumento" class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                <input type="text" id="numeroDocumento" name="numero_documento" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="primerNombre" class="form-label">Primer Nombre <span class="text-danger">*</span></label>
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
                                <label for="primerApellido" class="form-label">Primer Apellido <span class="text-danger">*</span></label>
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
                                <label for="correo" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" id="correo" name="correo" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <input type="tel" id="telefono" name="telefono" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección de Residencia <span class="text-danger">*</span></label>
                        <input type="text" id="direccion" name="direccion" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rol" class="form-label">Rol <span class="text-danger">*</span></label>
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
// ── Flash mensajes ──────────────────────────────────────────
function mostrarFlash(mensaje, tipo) {
    const iconos = { success: '&#10003;', error: '&#10007;', warning: '&#9888;' };
    const icono  = iconos[tipo] || '&#9432;';

    const div = document.createElement('div');
    div.className = 'flash-msg ' + tipo;
    div.innerHTML =
        '<span class="flash-icon">' + icono + '</span>' +
        '<span class="flash-text">' + mensaje + '</span>' +
        '<button class="flash-close" onclick="cerrarFlash(this)">&#10005;</button>';

    document.getElementById('flashContainer').appendChild(div);

    setTimeout(() => cerrarFlash(div.querySelector('.flash-close')), 4000);
}

function cerrarFlash(btn) {
    const div = btn.closest('.flash-msg');
    if (div) {
        div.style.animation = 'fadeOut 0.3s ease forwards';
        setTimeout(() => { if (div.parentNode) div.parentNode.removeChild(div); }, 300);
    }
}

// Variables globales para almacenar datos
let tiposDocumento = [];
let roles = [];
let datosYaCargados = false;

// Cargar tipos de documento y roles al iniciar

// Restricción de campos según rol
var idRolSesion = <?php echo isset($_SESSION['id_rol']) ? (int)$_SESSION['id_rol'] : 0; ?>;

function aplicarRestriccionesRol() {
    var camposSoloAdmin = ['tipoDocumento', 'numeroDocumento', 'rol', 'contrasena'];
    var esAdmin = (idRolSesion === 1);
    for (var i = 0; i < camposSoloAdmin.length; i++) {
        var el = document.getElementById(camposSoloAdmin[i]);
        if (el) {
            el.disabled = !esAdmin;
            var contenedor = el.closest('.mb-3');
            if (contenedor) {
                contenedor.style.opacity = esAdmin ? '1' : '0.5';
                contenedor.title = esAdmin ? '' : 'Solo el administrador puede modificar este campo';
            }
        }
    }
}


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
            
            aplicarRestriccionesRol();
            var modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
            modal.show();
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los datos del usuario:', status, error);
            mostrarFlash('Error al cargar los datos del usuario', 'error');
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
            mostrarFlash('Error al cargar los datos del usuario', 'error');
        }
    });
}

// Refrescar la tabla de usuarios vía AJAX
function refrescarTabla() {
    const busqueda = document.getElementById('filtro') ? document.getElementById('filtro').value : '';
    $.ajax({
        url: '<?php echo getUrl('usuarios','usuarios','filtro',false,'ajax'); ?>',
        type: 'GET',
        data: { buscar: busqueda },
        success: function(html) {
            document.getElementById('usuariosFiltro').innerHTML = html;
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
                        bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario')).hide();
                        mostrarFlash('Usuario actualizado correctamente', 'success');
                        refrescarTabla();
                    } else {
                        mostrarFlash(response.message || 'No se pudo actualizar el usuario', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                    mostrarFlash('Error al actualizar el usuario', 'error');
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
        mostrarFlash('Estado del usuario actualizado correctamente', 'success');
        refrescarTabla();
    } else {
        mostrarFlash(response.message || 'No se pudo cambiar el estado', 'error');
    }
},
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                    mostrarFlash('Error al cambiar el estado', 'error');
                }
            });
        });
    }
    
    // Cargar datos al iniciar la página
    cargarDatos();
});
</script>