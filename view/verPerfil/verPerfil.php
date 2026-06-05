<?php
if (!isset($perfil) || !is_array($perfil)) {
    echo "<div class='alert alert-danger mt-3'>No hay datos del perfil. Perfil: " . var_export($perfil, true) . "</div>";
    return;
}
?>

<style>
    .perfil-card {
        max-width: 860px;
        margin: 0 auto;
    }

    .perfil-card .form-control[readonly],
    .perfil-card .form-select:disabled {
        background-color: #f8f9fa;
        color: #495057;
        opacity: 1;
        cursor: default;
    }
</style>

<div class="card shadow-sm perfil-card">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Mis Datos personales</h4>
    </div>
    <div class="card-body">
        <?php if (isset($_SESSION['error_perfil'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_SESSION['error_perfil']); unset($_SESSION['error_perfil']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['exito_perfil'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_SESSION['exito_perfil']); unset($_SESSION['exito_perfil']); ?>
            </div>
        <?php endif; ?>

        <form id="formPerfil" action="index.php?modulo=perfil&controlador=perfil&funcion=actualizar" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_tipo_documento" class="form-label">Tipo de documento</label>
                    <select class="form-select perfil-campo" id="id_tipo_documento" name="id_tipo_documento" required disabled>
                        <option value="">Seleccione...</option>
                        <option value="1" <?php echo ($perfil['id_tipo_documento'] == 1 ? 'selected' : ''); ?>>Cedula de Ciudadania</option>
                        <option value="2" <?php echo ($perfil['id_tipo_documento'] == 2 ? 'selected' : ''); ?>>Cedula de Extranjeria</option>
                        <option value="3" <?php echo ($perfil['id_tipo_documento'] == 3 ? 'selected' : ''); ?>>Pasaporte</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="numero_documento" class="form-label">Numero de identificacion</label>
                    <input type="number" class="form-control perfil-campo" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars($perfil['numero_documento'] ?? ''); ?>" required readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="primer_nombre" class="form-label">Primer nombre</label>
                    <input type="text" class="form-control perfil-campo" id="primer_nombre" name="primer_nombre" value="<?php echo htmlspecialchars($perfil['primer_nombre'] ?? ''); ?>" required readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundo_nombre" class="form-label">Segundo nombre</label>
                    <input type="text" class="form-control perfil-campo" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars($perfil['segundo_nombre'] ?? ''); ?>" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="primer_apellido" class="form-label">Primer apellido</label>
                    <input type="text" class="form-control perfil-campo" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars($perfil['primer_apellido'] ?? ''); ?>" required readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundo_apellido" class="form-label">Segundo apellido</label>
                    <input type="text" class="form-control perfil-campo" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars($perfil['segundo_apellido'] ?? ''); ?>" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label">Correo electronico</label>
                    <input type="email" class="form-control perfil-campo" id="correo" name="correo" value="<?php echo htmlspecialchars($perfil['correo'] ?? ''); ?>" required readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="number" class="form-control perfil-campo" id="telefono" name="telefono" value="<?php echo htmlspecialchars($perfil['telefono'] ?? ''); ?>" required readonly>
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Direccion de residencia</label>
                <input type="text" class="form-control perfil-campo" id="direccion" name="direccion" value="<?php echo htmlspecialchars($perfil['direccion'] ?? ''); ?>" required readonly>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label" for="contrasena">Contrasena</label>
                <input type="password" class="form-control" id="contrasena" value="********" readonly>
                <small class="text-muted">Por seguridad, la contrasena no se muestra desde el perfil.</small>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-3">
                <button type="button" class="btn btn-primary" id="btnEditar">Actualizar datos</button>
                <button type="submit" class="btn btn-success d-none" id="btnGuardar">Guardar cambios</button>
                <button type="button" class="btn btn-secondary d-none" id="btnCancelar">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    var campos = document.querySelectorAll('.perfil-campo');
    var btnEditar = document.getElementById('btnEditar');
    var btnGuardar = document.getElementById('btnGuardar');
    var btnCancelar = document.getElementById('btnCancelar');

    btnEditar.addEventListener('click', function () {
        campos.forEach(function (campo) {
            if (campo.tagName === 'SELECT') {
                campo.disabled = false;
            } else {
                campo.readOnly = false;
            }
        });
        btnEditar.classList.add('d-none');
        btnGuardar.classList.remove('d-none');
        btnCancelar.classList.remove('d-none');
    });

    btnCancelar.addEventListener('click', function () {
        window.location.reload();
    });
})();
</script>
