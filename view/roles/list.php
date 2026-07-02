<!-- Contenedor flash -->
<div id="flashContainer" style="position:fixed;top:20px;right:20px;z-index:9999;min-width:300px;max-width:400px;"></div>

<?php if (isset($_SESSION['exito_rol'])): ?>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        mostrarFlash('<?php echo $_SESSION['exito_rol']; ?>', 'success');
    });
</script>
<?php unset($_SESSION['exito_rol']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_rol'])): ?>
<script>
    window.addEventListener('DOMContentLoaded', function() {
        mostrarFlash('<?php echo $_SESSION['error_rol']; ?>', 'error');
    });
</script>
<?php unset($_SESSION['error_rol']); ?>
<?php endif; ?>

<div class="container-fluid mt-4">

    <h1 class="mb-2">Listado de Roles</h1>

    <p class="text-muted">
        Gestión de roles registrados en el sistema
    </p>

    <div class="card shadow-sm border-0">

        <div class="card-header text-white" style="background:#22314d;">
            Roles registrados
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($rol = pg_fetch_assoc($roles)): ?>
                            <tr>
                                <td><?php echo $rol['id_rol']; ?></td>
                                <td><?php echo $rol['nombre_rol']; ?></td>
                                <td>
                                    <a href="<?php echo getUrl('roles', 'roles', 'getUpdate', array('id_rol' => $rol['id_rol'])); ?>">
                                        <button type="button" class="btn btn-primary btn-sm">
                                            Editar
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<link rel="stylesheet" href="assets/css/listaUsuarios.css">

<script>
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
</script>