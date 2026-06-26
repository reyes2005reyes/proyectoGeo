<?php
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 'ok') {
    redirect('/proyectoGeo/web/login.php');
    exit;
}
?>


<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold">Nueva Solicitud</h2>
        <p class="text-muted">Complete los campos para registrar una nueva solicitud</p>
    </div>

    <form action="<?php echo getUrl('solicitudes', 'solicitudes', 'postCreate'); ?>" method="post" enctype="multipart/form-data" id="formSolicitud">

        <div class="card shadow-sm mb-4">
            <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
                <i class="fa fa-file-alt me-2"></i> Información General
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="id_tipo_solicitud" class="form-label fw-semibold">Tipo de Solicitud</label>
                        <select class="form-select" id="id_tipo_solicitud" name="id_tipo_solicitud" required>
                            <option value="">Seleccione un tipo</option>
                            // no muestar pqrsf
                            <?php foreach ($tipos as $tipo) { 
                                if($tipo['codigo'] != 'pqrsf') {
                                    echo '<option value="' . $tipo['id_tipo_solicitud'] . '" data-codigo="' . htmlspecialchars($tipo['codigo']) . '">' . htmlspecialchars($tipo['nombre']) . '</option>';
                                }
                            ?>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="id_estado_solicitud" name="id_estado_solicitud" value="1">
                    </div>

                    <div class="col-md-8">
                        <label for="direccion" class="form-label fw-semibold">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ej: Calle 5 # 10-20" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Coordenada X</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                            <input type="text" class="form-control" id="coord_x_visual" placeholder="Seleccione en el mapa" readonly>
                        </div>
                        <input type="hidden" id="coord_x" name="coord_x">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Coordenada Y</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                            <input type="text" class="form-control" id="coord_y_visual" placeholder="Seleccione en el mapa" readonly>
                        </div>
                        <input type="hidden" id="coord_y" name="coord_y">
                    </div>

                    <!-- Imagen: oculta si es PQRSF -->
                    <div class="col-12" id="campo-imagen">
                        <label for="imagen" class="form-label fw-semibold">Imagen</label>
                        <input type="file" class="form-control" id="imagen" name="imagen" accept=".jpg,.jpeg,.png" required>
                        <div class="form-text">Formatos permitidos: JPG, JPEG, PNG</div>
                    </div>

                    <div class="col-12">
                        <label for="descripcion" class="form-label fw-semibold">Descripción General</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4"
                                  placeholder="Describa detalladamente la solicitud..." required></textarea>
                    </div>

                </div>
            </div>
        </div>

        <!-- Secciones dinámicas por tipo -->
        <div class="mt-3 border-top pt-4 d-none tipo-section" data-tipo="reporte_accidente">
            <?php include dirname(__FILE__) . '/forms/reporte_accidente.php'; ?>
        </div>
        <div class="mt-3 border-top pt-4 d-none tipo-section" data-tipo="senal_mal_estado">
            <?php include dirname(__FILE__) . '/forms/senal_mal_estado.php'; ?>
        </div>
        <div class="mt-3 border-top pt-4 d-none tipo-section" data-tipo="nueva_senalizacion">
            <?php include dirname(__FILE__) . '/forms/nueva_senalizacion.php'; ?>
        </div>
        <div class="mt-3 border-top pt-4 d-none tipo-section" data-tipo="reductor_mal_estado">
            <?php include dirname(__FILE__) . '/forms/reductor_mal_estado.php'; ?>
        </div>
        <div class="mt-3 border-top pt-4 d-none tipo-section" data-tipo="nuevo_reductor">
            <?php include dirname(__FILE__) . '/forms/nuevo_reductor.php'; ?>
        </div>
        <div class="mt-3 border-top pt-4 d-none tipo-section" data-tipo="via_publica_mal_estado">
            <?php include dirname(__FILE__) . '/forms/via_publica_mal_estado.php'; ?>
        </div>
        

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success px-4">
                <i class="fa fa-save me-1"></i> Registrar
            </button>
            <a class="btn btn-secondary px-4" href="<?php echo getUrl('solicitudes', 'solicitudes', 'listar', false); ?>">
                <i class="fa fa-arrow-left me-1"></i> Volver
            </a>
        </div>

    </form>
</div>

<script src="/proyectoGeo/web/js/createSoli.js"></script>
<script src="/proyectoGeo/web/js/capturarCoordenadas.js"></script>
