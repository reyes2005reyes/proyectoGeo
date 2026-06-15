
<?php
if (!isset($perfil) || !is_array($perfil)) {
    echo "<div class='alert alert-danger mt-3'>No hay datos del perfil. Perfil: No disponible</div>";
    return;
}
?>

<link rel="stylesheet" href="/proyectoGeo/web/assets/css/verPerfil.css">

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

        <!-- Aqui van a estar los campos del perfil que el usuario puede ver -->
        <form action="<?php echo getUrl('usuarios', 'usuarios', 'actualizar', false); ?>" method="POST">
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
                    <input type="number" class="form-control perfil-campo" id="numero_documento" name="numero_documento" value="<?php echo htmlspecialchars(isset($perfil['numero_documento']) ? $perfil['numero_documento'] : ''); ?>" required readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="primer_nombre" class="form-label">Primer nombre</label>
                    <input type="text" class="form-control perfil-campo" id="primer_nombre" name="primer_nombre" value="<?php echo htmlspecialchars(isset($perfil['primer_nombre']) ? $perfil['primer_nombre'] : ''); ?>" required readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundo_nombre" class="form-label">Segundo nombre</label>
                    <input type="text" class="form-control perfil-campo" id="segundo_nombre" name="segundo_nombre" value="<?php echo htmlspecialchars(isset($perfil['segundo_nombre']) ? $perfil['segundo_nombre'] : ''); ?>" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="primer_apellido" class="form-label">Primer apellido</label>
                    <input type="text" class="form-control perfil-campo" id="primer_apellido" name="primer_apellido" value="<?php echo htmlspecialchars(isset($perfil['primer_apellido']) ? $perfil['primer_apellido'] : ''); ?>" required readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="segundo_apellido" class="form-label">Segundo apellido</label>
                    <input type="text" class="form-control perfil-campo" id="segundo_apellido" name="segundo_apellido" value="<?php echo htmlspecialchars(isset($perfil['segundo_apellido']) ? $perfil['segundo_apellido'] : ''); ?>" readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="correo" class="form-label">Correo electronico</label>
                    <input type="email" class="form-control perfil-campo" id="correo" name="correo" value="<?php echo htmlspecialchars(isset($perfil['correo']) ? $perfil['correo'] : ''); ?>" required readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="number" class="form-control perfil-campo" id="telefono" name="telefono" value="<?php echo htmlspecialchars(isset($perfil['telefono']) ? $perfil['telefono'] : ''); ?>" required readonly>
                </div>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Direccion de residencia</label>
                <input type="text" class="form-control perfil-campo" id="direccion" name="direccion" value="<?php echo htmlspecialchars(isset($perfil['direccion']) ? $perfil['direccion'] : ''); ?>" required readonly>
            </div>

            <!-- Para la contraseña se usara un modal -->
            <div class="mb-3">
                <label class="form-label">Contraseña</label>

                <div class="input-group">
                    <input type="password"
                        class="form-control"
                        value="********"
                        readonly>

                    <button type="button"
                            class="btn btn-warning"
                            id="btnCambiarContrasena">
                        Actualizar contraseña
                    </button>
                </div>

                <small class="text-muted">
                    Por seguridad la contraseña no se muestra.
                </small>
            </div>
            <!-- Estos son los botones que vera el usuario nada mas entrar al modulo -->
            <div class="d-flex gap-2 justify-content-end mt-3">
                <button type="button" class="btn btn-primary" id="btnEditar">Actualizar datos</button>
                <button type="submit" class="btn btn-success d-none" id="btnGuardar">Guardar cambios</button>
                <button type="button" class="btn btn-secondary d-none" id="btnCancelar">Cancelar</button>
            </div>
        </form>
    </div>
</div>


<!-- Aqui comienza el modal que se abrira para actualizar la contraseña -->
<div class="modal fade" id="modalContrasena" tabindex="-1" role="dialog" aria-labelledby="modalContrasenaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form action="<?php echo getUrl('usuarios', 'usuarios', 'actualizarContrasena', false); ?>" method="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalContrasenaLabel">
                        Actualizar contraseña
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Cerrar">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="contrasena_actual" class="form-label">
                            Contraseña actual
                        </label>

                        <input type="password"
                               class="form-control"
                               id="contrasena_actual"
                               name="contrasena_actual"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="nueva_contrasena" class="form-label">
                            Nueva contraseña
                        </label>

                        <input type="password"
                               class="form-control"
                               id="nueva_contrasena"
                               name="nueva_contrasena"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="confirmar_contrasena" class="form-label">
                            Confirmar contraseña
                        </label>

                        <input type="password"
                               class="form-control"
                               id="confirmar_contrasena"
                               name="confirmar_contrasena"
                               required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Guardar cambios
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<script src="/../../web/js/verPerfil.js"></script>