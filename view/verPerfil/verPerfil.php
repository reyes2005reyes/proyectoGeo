
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
            <!-- CABECERA PERFIL -->
            <div class="perfil-header text-center mb-4">

                <div class="avatar-perfil">
                    <?php echo strtoupper(substr($perfil['primer_nombre'], 0, 1)); ?>
                </div>

                <h4 class="mt-3">
                    <?php echo htmlspecialchars($perfil['primer_nombre'].' '.$perfil['primer_apellido']); ?>
                </h4>

                <p class="text-muted">
                    <?php echo htmlspecialchars($perfil['correo']); ?>
                </p>
            </div>

            <!-- DATOS DE IDENTIFICACIÓN -->
            <div class="seccion-perfil">
                <div class="cabecera-seccion">
                    Datos de identificación
                </div>
                <div class="contenido-seccion">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de documento</label>
                            <input type="text"
                                class="form-control"
                                value="<?php echo htmlspecialchars($perfil['nombre_tipo_documento']); ?>"
                                readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de identificación</label>
                            <input type="text"
                                class="form-control"
                                value="<?php echo htmlspecialchars($perfil['numero_documento']); ?>"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMACIÓN PERSONAL -->
            <div class="seccion-perfil">
                <div class="cabecera-seccion">
                    Información personal
                </div>
                <div class="contenido-seccion">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="primer_nombre" class="form-label"> Primer nombre </label>
                            <input type="text"
                                class="form-control perfil-campo"
                                id="primer_nombre"
                                name="primer_nombre"
                                value="<?php echo htmlspecialchars($perfil['primer_nombre']); ?>"
                                readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="segundo_nombre" class="form-label"> Segundo nombre </label>
                            <input type="text"
                                class="form-control perfil-campo"
                                id="segundo_nombre"
                                name="segundo_nombre"
                                value="<?php echo htmlspecialchars($perfil['segundo_nombre']); ?>"
                                readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="primer_apellido" class="form-label"> Primer apellido </label>
                            <input type="text"
                                class="form-control perfil-campo"
                                id="primer_apellido"
                                name="primer_apellido"
                                value="<?php echo htmlspecialchars($perfil['primer_apellido']); ?>"
                                readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="segundo_apellido" class="form-label"> Segundo apellido </label>
                            <input type="text"
                                class="form-control perfil-campo"
                                id="segundo_apellido"
                                name="segundo_apellido"
                                value="<?php echo htmlspecialchars($perfil['segundo_apellido']); ?>"
                                readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMACIÓN DE CONTACTO -->
            <div class="seccion-perfil">

                <div class="cabecera-seccion">
                    Información de contacto
                </div>

                <div class="contenido-seccion">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="correo" class="form-label">
                                Correo electrónico
                            </label>

                            <input type="email"
                                class="form-control perfil-campo"
                                id="correo"
                                name="correo"
                                value="<?php echo htmlspecialchars($perfil['correo']); ?>"
                                readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">
                                Teléfono
                            </label>

                            <input type="number"
                                class="form-control perfil-campo"
                                id="telefono"
                                name="telefono"
                                value="<?php echo htmlspecialchars($perfil['telefono']); ?>"
                                readonly>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">
                            Dirección de residencia
                        </label>

                        <input type="text"
                            class="form-control perfil-campo"
                            id="direccion"
                            name="direccion"
                            value="<?php echo htmlspecialchars($perfil['direccion']); ?>"
                            readonly>
                    </div>

                </div>

            </div>

            <!-- SEGURIDAD -->
            <div class="seccion-perfil">

                <div class="cabecera-seccion">
                    Seguridad
                </div>

                <div class="contenido-seccion">

                    <label class="form-label">
                        Contraseña
                    </label>

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

            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">

                <button type="button"
                        class="btn btn-primary"
                        id="btnEditar">
                    Actualizar datos
                </button>

                <button type="submit"
                        class="btn btn-success d-none"
                        id="btnGuardar">
                    Guardar cambios
                </button>

                <button type="button"
                        class="btn btn-secondary d-none"
                        id="btnCancelar">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script src="/../../web/js/verPerfil.js"></script>