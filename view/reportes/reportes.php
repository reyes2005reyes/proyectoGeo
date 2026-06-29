<?php

$id_rol = isset($_SESSION['id_rol']) ? (int)$_SESSION['id_rol'] : 0;

if (!estaLogueado() || !tienePermiso('Reportes', 'listar')) {
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
<!-- Grafica de solicitudes por tipo -->
<div class="row mb-4">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color:#1A3C5E;">
                <div class="card-title text-white mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Solicitudes registradas por tipo
                </div>
            </div>
            <div class="card-body text-center">
                <?php if (!empty($totales_tipo)): ?>
                    <canvas id="graficaTipo" style="max-height:350px;"></canvas>
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
                    <script>
                    (function() {
                        var labels = [];
                        var datos  = [];
                        var colores = [
                            '#1A3C5E','#2E6DA4','#3498DB',
                            '#27AE60','#F39C12','#E74C3C',
                            '#9B59B6','#1ABC9C','#E67E22','#95A5A6'
                        ];
                         //   Carga los datos obtenidos desde PHP
                        <?php foreach ($totales_tipo as $t): ?>
                            labels.push('<?php echo addslashes($t['tipo']); ?>');
                            datos.push(<?php echo (int)$t['total']; ?>);
                        <?php endforeach; ?>

                        var total = datos.reduce(function(a, b) { return a + b; }, 0);
                            // Obtiene el contexto del canvas donde se dibujara
                        var ctx = document.getElementById('graficaTipo').getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labels,
                                datasets: [{
                                    data: datos,
                                    backgroundColor: colores.slice(0, labels.length),
                                    borderWidth: 2,
                                    borderColor: '#fff'
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { position: 'right' },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                var val = context.parsed;
                                                var pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                                                return context.label + ': ' + val + ' (' + pct + '%)';
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    })();
                    </script>
                <?php else: ?>
                    <div class="text-muted py-4">
                        <i class="fas fa-chart-pie fa-2x mb-2 d-block"></i>
                        No hay solicitudes registradas aún.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
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
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo date('Y-m-d'); ?>" required max="<?php echo date('Y-m-d'); ?>">
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
<div class="row mt-4">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow-sm">
            <div class="card-header" style="background-color:#1A3C5E;">
                <div class="card-title text-white mb-0">
                    <i class="fas fa-history me-2"></i>
                    Historial de reportes generados
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($historial)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipo de reporte</th>
                                    <th>Rango de fechas</th>
                                    <th>Generado por</th>
                                    <th>Fecha de generación</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tipos = array(
                                    'accidentes' => 'Accidentes de tránsito',
                                    'senales'    => 'Señalización vial en mal estado',
                                    'reductores' => 'Reductores de velocidad en mal estado'
                                );
                                foreach ($historial as $h):
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars(isset($tipos[$h['tipo_reporte']]) ? $tipos[$h['tipo_reporte']] : $h['tipo_reporte']); ?></td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($h['fecha_inicio'])); ?> — <?php echo date('d/m/Y', strtotime($h['fecha_fin'])); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($h['primer_nombre'] . ' ' . $h['primer_apellido']); ?></td>
                                        <td><?php echo date('d/m/Y h:i A', strtotime($h['fecha_generacion'])); ?></td>
                                        <td class="text-center">
                                            <form method="POST" action="<?php echo getUrl('reportes','reportes','descargar', false, 'ajax'); ?>">
                                                <input type="hidden" name="tipo_reporte" value="<?php echo htmlspecialchars($h['tipo_reporte']); ?>">
                                                <input type="hidden" name="fecha_inicio" value="<?php echo htmlspecialchars($h['fecha_inicio']); ?>">
                                                <input type="hidden" name="fecha_fin" value="<?php echo htmlspecialchars($h['fecha_fin']); ?>">
                                                <input type="hidden" name="estado" value="<?php echo htmlspecialchars($h['id_estado_solicitud']); ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download me-1"></i>
                                                    Descargar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                        Aún no se han generado reportes.
                    </div>
                <?php endif; ?>
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