<?php

$id_rol = isset($_SESSION['id_rol']) ? (int)$_SESSION['id_rol'] : 0;

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== 'ok' ||
    !in_array($id_rol, array(1, 2))) {
    redirect('/proyectoGeo/web/login.php');
    exit;
}
?>

<div class="page-header">
    <h4 class="page-title">Reportes</h4>
</div>

<?php if (isset($_SESSION['error_reportes'])): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_SESSION['error_reportes']); unset($_SESSION['error_reportes']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color:#1A3C5E;">
                <div class="card-title text-white mb-0">
                    <i class="fas fa-sliders-h me-2"></i>
                    Panel de reportes
                </div>
            </div> 
            <div class="card-body">

                <div class="alert alert-info mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Seleccione el tipo de reporte, configure los filtros y presione
                    <strong>Descargar Reporte (XLSX)</strong> para obtener el archivo.
                </div>

                <form id="formReporte" method="POST" action="<?php echo getUrl('reportes','reportes','descargar', false, "ajax"); ?>">

                    <div class="row g-3">

                        <!-- 1. Tipo de reporte -->
                        <div class="col-md-12">
                            <label for="tipo_reporte" class="form-label fw-bold">
                                <i class="fas fa-list me-1 text-primary"></i>
                                Tipo de reporte <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="tipo_reporte" name="tipo_reporte" required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <option value="accidentes">Reporte de accidentes de tránsito</option>
                                <option value="senales">Reporte de señalización vial en mal estado</option>
                                <option value="reductores">Reporte de reductores de velocidad en mal estado</option>
                            </select>
                        </div>

                        <!-- ── 2. Rango de fechas -->
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                Fecha de inicio <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            <!-- Mensaje de error Excepcion 2 -->
                            <div id="error_fecha" class="text-danger small mt-1 d-none">
                                <i class="fas fa-exclamation-circle me-1"></i>
                                La fecha inicial no puede ser mayor a la fecha final de la consulta.
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label fw-bold">
                                <i class="fas fa-calendar-check me-1 text-primary"></i>
                                Fecha de fin <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>

                        <!-- 3. Filtro estado -->
                        <div class="col-md-12">
                            <label for="estado" class="form-label fw-bold">
                                <i class="fas fa-filter me-1 text-primary"></i>
                                Estado de la solicitud
                                <span> (opcional)</span>
                            </label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Todos los estados</option>
                                <?php if (!empty($estados)): ?>
                                    <?php foreach ($estados as $est): ?>
                                        <option value="<?php echo (int)$est['id_estado_solicitud']; ?>">
                                            <?php echo htmlspecialchars($est['nombre_estado_solicitud']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                    </div>

                    <!--Boton de generacion -->
                    <div class="mt-4 text-end">
                        <button type="submit" id="btnDescargar" class="btn btn-primary px-4">
                            <i class="fas fa-file-excel me-2"></i>
                            Descargar Reporte (XLSX)
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<script>
(function () {
    'use strict';

    const form = document.getElementById('formReporte');
    const inputInicio  = document.getElementById('fecha_inicio');
    const inputFin = document.getElementById('fecha_fin');
    const errorFecha = document.getElementById('error_fecha');
    const btnDescargar = document.getElementById('btnDescargar');

    // Validacion en tiempo real del rango de fechas
    function validarFechas() {
        const inicio = inputInicio.value;
        const fin = inputFin.value;

        if (inicio && fin && inicio > fin) {
            // Marcar campos en rojo y deshabilitar boton
            inputInicio.classList.add('is-invalid');
            inputFin.classList.add('is-invalid');
            errorFecha.classList.remove('d-none');
            btnDescargar.disabled = true;
            return false;
        }

        // Limpiar estado de error
        inputInicio.classList.remove('is-invalid');
        inputFin.classList.remove('is-invalid');
        errorFecha.classList.add('d-none');
        btnDescargar.disabled = false;
        return true;
    }

    inputInicio.addEventListener('change', validarFechas);
    inputFin.addEventListener('change', validarFechas);

    // Al enviar el formulario
    form.addEventListener('submit', function (e) {
        if (!validarFechas()) {
            e.preventDefault();
            return;
        }
    });
})();
</script>