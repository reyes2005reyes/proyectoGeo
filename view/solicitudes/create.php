<div class="mt-5">
    <h1 class="display-4">Nueva Solicitud</h1>
</div>
<div class="mt-4">
    <form action="<?php echo getUrl('solicitudes', 'solicitudes', 'postCreate'); ?>" method="post"  enctype="multipart/form-data" id="formSolicitud">
        <div class="row">
            <div class="col-md-4 mt-3">
                <label for="id_tipo_solicitud">Tipo</label>
                <select class="form-control" id="id_tipo_solicitud" name="id_tipo_solicitud" required>
                    <option value="">Seleccione</option>
                    <?php foreach ($tipos as $tipo) { ?>
                        <option value="<?php echo $tipo['id_tipo_solicitud']; ?>" data-codigo="<?php echo htmlspecialchars($tipo['codigo']); ?>">
                            <?php echo htmlspecialchars($tipo['nombre']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <input type="hidden" id="id_estado_solicitud" name="id_estado_solicitud" value="1">


            <div class="col-md-4 mt-3">
                <label for="direccion">Direccion</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required>
            </div>
        </div>

    <div class="row">

        <input type="hidden" id="coord_x" name="coord_x">
        <input type="hidden" id="coord_y" name="coord_y">

        <div class="col-md-6 mt-3">
            <label>Coordenada X</label>
            <input type="text"
                class="form-control"
                id="coord_x_visual"
                readonly>
        </div>

        <div class="col-md-6 mt-3">
            <label>Coordenada Y</label>
            <input type="text"
                class="form-control"
                id="coord_y_visual"
                readonly>
        </div>

    </div>

        <div class="mt-3">
            <label for="imagen">Imagen</label>
            <input type="file"
                class="form-control"
                id="imagen"
                name="imagen"
                accept=".jpg,.jpeg,.png">
        </div>

        <div class="mt-3">
            <label for="descripcion">Descripcion general</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
        </div>

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

        <div class="mt-4">
            <input type="submit" class="btn btn-success" value="Registrar">
            <a class="btn btn-secondary" href="<?php echo getUrl('solicitudes', 'solicitudes', 'listar', false); ?>">Volver</a>
        </div>
    </form>
</div>

<script src="/proyectoGeo/web/js/createSoli.js"></script>
<script src="/proyectoGeo/web/js/capturarCoordenadas.js"></script>