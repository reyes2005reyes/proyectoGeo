<?php
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 'ok') {
    redirect('/proyectoGeo/web/login.php');
    exit;
}
?>


<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold">Nueva solicitud</h2>
        <p class="text-muted">Complete los campos para registrar una nueva solicitud.</p>
    </div>

    <form action="<?php echo getUrl('solicitudes', 'solicitudes', 'postCreate'); ?>" method="post" enctype="multipart/form-data" id="formSolicitud">

        <div class="card shadow-sm mb-4">
            <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
                <i class="fa fa-file-alt me-2"></i> Información General
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                       <label for="id_tipo_solicitud" class="form-label fw-semibold">
                            Tipo de solicitud
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="id_tipo_solicitud" name="id_tipo_solicitud" required>
                            <option value="">Seleccione un tipo</option>
                             <!-- no muestar pqrsf -->
                            <?php foreach ($tipos as $tipo) { 
                                if($tipo['codigo'] != 'pqrsf') {
                                    echo '<option value="' . $tipo['id_tipo_solicitud'] . '" data-codigo="' . htmlspecialchars($tipo['codigo']) . '">' . htmlspecialchars($tipo['nombre']) . '</option>';
                                }
                            ?>
                            <?php } ?>
                        </select>
                        <input type="hidden" id="id_estado_solicitud" name="id_estado_solicitud" value="1">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold"> Dirección de la solicitud </label>

                        <div class="small text-muted mb-3">
                            Los campos marcados con <span class="text-danger fw-bold">*</span> son obligatorios.
                        </div>

                        <div class="row g-2 align-items-end">

                            <!-- Tipo vía -->
                            <div class="col-md-3">
                                <label class="form-label">
                                    Tipo de vía <span class="text-danger">*</span>
                                </label>

                                <select class="form-select" id="tipo_via" required>
                                    <option value="">Seleccione</option>
                                    <option>Calle</option>
                                    <option>Carrera</option>
                                    <option>Avenida</option>
                                    <option>Diagonal</option>
                                    <option>Transversal</option>
                                    <option>Circular</option>
                                    <option>Autopista</option>
                                </select>
                            </div>

                            <!-- Número principal -->
                            <div class="col-md-1">
                                <label class="form-label">
                                    Número
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="number" class="form-control" id="numero1" min="1">
                            </div>

                            <!-- Letra -->
                            <div class="col-md-1">
                                <label class="form-label"> Letra </label>

                                <select class="form-select" id="letra1">
                                    <option value=""></option>

                                    <?php foreach(range('A','Z') as $letra){ ?>
                                        <option><?php echo $letra; ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <!-- Bis -->
                            <div class="col-md-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="bis">

                                    <label class="form-check-label" for="bis">
                                        Bis
                                        <i class="fa fa-question-circle text-primary" data-bs-toggle="tooltip"
                                            title="Marque esta opción únicamente si la dirección contiene la palabra 'Bis'. Ejemplo: Calle 15 Bis # 20-30.">
                                        </i>
                                    </label>
                                </div>
                            </div>

                            <!-- Número # -->
                            <div class="col-md-1">
                                <label class="form-label">
                                    #
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="numero2" min="1">
                            </div>

                            <!-- Letra secundaria -->
                            <div class="col-md-1">
                                <label class="form-label">
                                    Letra
                                </label>
                                <select class="form-select" id="letra2">
                                    <option value=""></option>
                                    <?php foreach(range('A','Z') as $letra){ ?>
                                        <option><?php echo $letra; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- Número final -->
                            <div class="col-md-1">
                                <label class="form-label">
                                    -
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="numero3" min="1">
                            </div>

                            <!-- Complemento -->
                            <div class="col-md-2">
                               <label class="form-label">
                                Complemento

                                <i class="fa fa-question-circle text-primary" data-bs-toggle="tooltip"
                                title="Ingrese información adicional para ubicar el lugar, por ejemplo: Apartamento 302, Torre B, Local 5, Interior 2 o Casa 10. Si la dirección no tiene complemento, deje este campo vacío.">
                                </i>
                            </label>

                            <input type="text" class="form-control" id="complemento" placeholder="Ej: Apto 302, Torre B, Local 5">
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold"> Dirección generada </label>

                            <input type="text" class="form-control" id="direccion_preview" readonly>

                            <div id="errorDireccion" class="text-danger small mt-2" style="display:none;"> </div>
                        </div>
                        <input type="hidden" id="direccion" name="direccion">
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
                        <label for="imagen" class="form-label fw-semibold">
                            Imagen
                            <span class="text-danger">*</span>
                              <i class="fa fa-question-circle text-primary" data-bs-toggle="tooltip"
                               title="Sube una fotografía clara del lugar donde se presenta la situación o donde se solicita la intervención. La imagen debe mostrar la zona afectada o el sitio donde se requiere la señalización, el reductor u otra acción.">
                              </i>
                        </label>
                        <input type="file" class="form-control" id="imagen" name="imagen" 
                            accept=".jpg,.jpeg,.png" required>
                        <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Máximo 5 MB.</div>
                        <div id="error-imagen" class="text-danger small mt-1" style="display:none;"></div>
                    </div>

                    <div class="col-12">
                        <label for="descripcion" class="form-label fw-semibold">
                            Descripción General
                            <span class="text-danger">*</span>
                        </label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="5" placeholder="Describa detalladamente la solicitud..." required></textarea>
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
<script>
document.getElementById('imagen').addEventListener('change', function() {
    var error = document.getElementById('error-imagen');
    var maxSize = 5 * 1024 * 1024; // 5 MB en bytes

    if (this.files[0] && this.files[0].size > maxSize) {
        error.textContent = 'La imagen no puede superar los 5 MB.';
        error.style.display = 'block';
        this.value = ''; // Limpia el input
    } else {
        error.style.display = 'none';
    }
});
</script>
