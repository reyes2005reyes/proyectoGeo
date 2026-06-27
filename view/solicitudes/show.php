<?php if (isset($_SESSION['respuesta_exitosa'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $_SESSION['respuesta_exitosa']; unset($_SESSION['respuesta_exitosa']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Detalle Solicitud</h2>
            <p class="text-muted mb-0">#<?php echo htmlspecialchars($solicitud['id_solicitud']); ?></p>
        </div>
        <a class="btn btn-secondary px-4"
           href="<?php echo getUrl('solicitudes', 'solicitudes', 'listar', false); ?>">
            <i class="fa fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <!-- Información de la solicitud -->
    <div class="card shadow-sm mb-4">
        <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
            <i class="fa fa-file-alt me-2"></i> Información General
        </div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-4">
                    <p class="text-muted mb-0 fw-semibold">Tipo</p>
                    <p><?php echo htmlspecialchars($solicitud['nombre_tipo_solicitud']); ?></p>
                </div>

                <div class="col-md-4">
                    <p class="text-muted mb-0 fw-semibold">Estado</p>
                    <span class="badge bg-secondary">
                        <?php echo htmlspecialchars($solicitud['nombre_estado_solicitud']); ?>
                    </span>
                </div>

                <div class="col-md-4">
                    <p class="text-muted mb-0 fw-semibold">Fecha</p>
                    <p><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></p>
                </div>

                <div class="col-md-4">
                    <p class="text-muted mb-0 fw-semibold">Usuario</p>
                    <p><?php echo htmlspecialchars($solicitud['primer_nombre'] . ' ' . $solicitud['primer_apellido']); ?></p>
                </div>

                <div class="col-md-4">
                    <p class="text-muted mb-0 fw-semibold">Dirección</p>
                    <p><?php echo htmlspecialchars($solicitud['direccion']); ?></p>
                </div>

                <?php if (!empty($solicitud['coord_x'])) { ?>
                <div class="col-md-2">
                    <p class="text-muted mb-0 fw-semibold">Coordenada X</p>
                    <p><?php echo htmlspecialchars($solicitud['coord_x']); ?></p>
                </div>
                <div class="col-md-2">
                    <p class="text-muted mb-0 fw-semibold">Coordenada Y</p>
                    <p><?php echo htmlspecialchars($solicitud['coord_y']); ?></p>
                </div>
                <?php } ?>

                <div class="col-12">
                    <p class="text-muted mb-0 fw-semibold">Descripción</p>
                    <p><?php echo nl2br(htmlspecialchars($solicitud['descripcion'])); ?></p>
                </div>

                <?php if (!empty($solicitud['imagen_url'])) {
                    $imagenUrl = $solicitud['imagen_url'];
                    if (strpos($imagenUrl, '../web/') === 0) {
                        $imagenUrl = '/proyectoGeo/web/' . substr($imagenUrl, strlen('../web/'));
                    } elseif (strpos($imagenUrl, 'web/assets/') === 0) {
                        $imagenUrl = '/proyectoGeo/' . $imagenUrl;
                    }
                ?>
                <div class="col-12">
                    <p class="text-muted mb-0 fw-semibold">Imagen</p>
                    <img src="<?php echo htmlspecialchars($imagenUrl); ?>"
                         alt="Imagen de la solicitud"
                         class="img-fluid rounded mt-2"
                         style="max-width: 300px;">
                </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <!-- Responder solicitud -->
    <?php if ($_SESSION['id_rol'] == 2 ) { ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
            <i class="fa fa-reply me-2"></i> Responder Solicitud
        </div>
        <div class="card-body">
            <form action="<?php echo getUrl('solicitudes','solicitudes','postResponder'); ?>" method="post">
                <input type="hidden" name="id_solicitud" value="<?php echo $solicitud['id_solicitud']; ?>">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="id_estado_solicitud" class="form-label fw-semibold">Nuevo Estado</label>
                        <select class="form-select" id="id_estado_solicitud" name="id_estado_solicitud" required>
                            <?php while($estado = pg_fetch_assoc($estados)) { ?>
                                <option value="<?php echo $estado['id_estado_solicitud']; ?>">
                                    <?php echo htmlspecialchars($estado['nombre_estado_solicitud']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="mensaje" class="form-label fw-semibold">Respuesta</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="4" required
                                  placeholder="Escriba su respuesta..."></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn text-white px-4" style="background-color: #1a2942;">
                            <i class="fa fa-save me-1"></i> Guardar Respuesta
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>

    <!-- Historial de respuestas -->
    <div class="card shadow-sm">
        <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
            <i class="fa fa-history me-2"></i> Historial de Respuestas
        </div>
        <div class="card-body">
            <?php if (pg_num_rows($respuestas) > 0) { ?>
                <?php while($respuesta = pg_fetch_assoc($respuestas)) { ?>
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong><?php echo htmlspecialchars($respuesta['primer_nombre'] . ' ' . $respuesta['primer_apellido']); ?></strong>
                            <small class="text-muted"><?php echo htmlspecialchars($respuesta['fecha']); ?></small>
                        </div>
                        <span class="badge bg-secondary mb-2">
                            <?php echo htmlspecialchars($respuesta['nombre_estado_solicitud']); ?>
                        </span>
                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($respuesta['mensaje'])); ?></p>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-muted text-center py-3">
                    <i class="fa fa-inbox fa-2x d-block mb-2"></i>
                    No hay respuestas registradas.
                </p>
            <?php } ?>
        </div>
    </div>

</div>

<?php if ($_SESSION['id_rol'] == 2): ?>

<div id="loadingOverlay" style="
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(26, 41, 66, 0.75);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
">
    <div class="spinner-border text-light mb-3" style="width:3rem; height:3rem;" role="status"></div>
    <p class="text-white fw-semibold fs-5">Enviando respuesta y notificando al ciudadano...</p>
</div>

<script>
document.querySelector('form[action*="postResponder"]').addEventListener('submit', function(e) {
    e.preventDefault(); // Detiene el envío inmediato

    var form = this;
    var overlay = document.getElementById('loadingOverlay');
    overlay.style.display = 'flex';

    // Espera mínimo 2 segundos antes de enviar
    setTimeout(function() {
        form.submit();
    }, 2000); // 2000ms = 2 segundos
});
</script>

<?php endif; ?>