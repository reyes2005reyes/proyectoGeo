<div class="mt-5">
    <h1 class="display-4">Actualizar Solicitud</h1>
</div>

<div class="mt-4">
    <form action="<?php echo getUrl('solicitudes', 'solicitudes', 'postUpdate'); ?>" method="post" enctype="multipart/form-data">>
        <input type="hidden" name="id_solicitud" value="<?php echo htmlspecialchars($solicitud['id_solicitud']); ?>">

        <div class="row">
            <div class="col-md-4 mt-3">
                <label for="id_tipo_solicitud">Tipo</label>
                <select class="form-control" id="id_tipo_solicitud" name="id_tipo_solicitud" required>
                    <?php foreach ($tipos as $tipo) { ?>
                        <option value="<?php echo $tipo['id_tipo_solicitud']; ?>" data-codigo="<?php echo htmlspecialchars($tipo['codigo']); ?>" <?php echo ((int)$tipo['id_tipo_solicitud'] === (int)$solicitud['id_tipo_solicitud']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tipo['nombre']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-4 mt-3">
                <label for="id_estado_solicitud">Estado</label>
                <select class="form-control" id="id_estado_solicitud" name="id_estado_solicitud" required>
                    <?php foreach ($estados as $estado) { ?>
                        <option value="<?php echo $estado['id_estado_solicitud']; ?>" <?php echo ((int)$estado['id_estado_solicitud'] === (int)$solicitud['id_estado_solicitud']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($estado['nombre_estado_solicitud']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-4 mt-3">
                <label for="direccion">Direccion</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo htmlspecialchars($solicitud['direccion']); ?>" required>
            </div>
        </div>

                    <!-- Coordenadas (se llenarán desde el mapa) -->
            <input type="hidden" id="coord_x" name="coord_x"
                value="<?php echo isset($solicitud['coord_x']) ? htmlspecialchars($solicitud['coord_x']) : ''; ?>">

            <input type="hidden" id="coord_y" name="coord_y"
                value="<?php echo isset($solicitud['coord_y']) ? htmlspecialchars($solicitud['coord_y']) : ''; ?>">

        <div class="mt-3">
            <label for="imagen">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept=".jpg,.jpeg,.png">
        </div>

        <div class="mt-3">
            <label for="descripcion">Descripcion</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($solicitud['descripcion']); ?></textarea>
        </div>

        <div class="mt-4">
            <input type="submit" class="btn btn-success" value="Actualizar">
            <a class="btn btn-secondary" href="<?php echo getUrl('solicitudes', 'solicitudes', 'listar', false); ?>">Volver</a>
        </div>
    </form>
</div>

<!-- Secciones por tipo (reutiliza partials) -->
<div class="mt-4 border-top pt-4 d-none tipo-section" data-tipo="reporte_accidente">
    <?php include dirname(__FILE__) . '/forms/reporte_accidente.php'; ?>
</div>

<div class="mt-4 border-top pt-4 d-none tipo-section" data-tipo="senal_mal_estado">
    <?php include dirname(__FILE__) . '/forms/senal_mal_estado.php'; ?>
</div>

<div class="mt-4 border-top pt-4 d-none tipo-section" data-tipo="nueva_senalizacion">
    <?php include dirname(__FILE__) . '/forms/nueva_senalizacion.php'; ?>
</div>

<div class="mt-4 border-top pt-4 d-none tipo-section" data-tipo="reductor_mal_estado">
    <?php include dirname(__FILE__) . '/forms/reductor_mal_estado.php'; ?>
</div>

<div class="mt-4 border-top pt-4 d-none tipo-section" data-tipo="nuevo_reductor">
    <?php include dirname(__FILE__) . '/forms/nuevo_reductor.php'; ?>
</div>

<div class="mt-4 border-top pt-4 d-none tipo-section" data-tipo="via_publica_mal_estado">
    <?php include dirname(__FILE__) . '/forms/via_publica_mal_estado.php'; ?>
</div>

<div class="mt-4 border-top pt-4 d-none tipo-section" data-tipo="pqrsf">
    <?php include dirname(__FILE__) . '/forms/pqrsf.php'; ?>
</div>


<script src="/proyectoGeo/web/js/createSoli.js"></script>