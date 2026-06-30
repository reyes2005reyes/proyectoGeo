<div class="card shadow-sm mt-4">
    <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
        <i class="fa fa-car-crash me-2"></i> Reporte de accidente
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-6">
                <label for="id_tipo_vehiculo" class="form-label fw-semibold">Tipo de vehículo</label>
                <select class="form-select detalle-required" id="id_tipo_vehiculo" name="id_tipo_vehiculo">
                    <option value="">Seleccione</option>
                    <?php foreach ($catalogos['tipos_vehiculo'] as $vehiculo) { ?>
                        <option value="<?php echo $vehiculo['id_tipo_vehiculo']; ?>"
                            <?php echo (isset($detalle['id_tipo_vehiculo']) && $detalle['id_tipo_vehiculo'] == $vehiculo['id_tipo_vehiculo']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($vehiculo['nombre_vehiculo']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="id_causa_accidente" class="form-label fw-semibold">Causa</label>
                <select class="form-select detalle-required" id="id_causa_accidente" name="id_causa_accidente">
                    <option value="">Seleccione</option>
                    <?php foreach ($catalogos['causas_accidente'] as $causa) { ?>
                        <option value="<?php echo $causa['id_causa_accidente']; ?>"
                            data-tipo-choque="<?php echo htmlspecialchars($causa['nombre_tipo_choque']); ?>"
                            <?php echo (isset($detalle['id_causa_accidente']) && $detalle['id_causa_accidente'] == $causa['id_causa_accidente']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($causa['nombre_causa']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="tipo_choque" class="form-label fw-semibold">Tipo de Choque</label>
                <input type="text" class="form-control" id="tipo_choque" readonly placeholder="Se completa automáticamente">
            </div>

            <div class="col-md-6">
                <label for="numero_lesionados" class="form-label fw-semibold">Cantidad de Lesionados</label>
                <input type="number" min="0" class="form-control"
                       id="numero_lesionados" name="numero_lesionados"
                       value="<?php echo isset($detalle['numero_lesionados']) ? $detalle['numero_lesionados'] : 0; ?>">
            </div>

        </div>
    </div>
</div>

<script>
function actualizarTipoChoque() {
    var causa = document.getElementById('id_causa_accidente');
    if (causa.selectedIndex > 0) {
        document.getElementById('tipo_choque').value =
            causa.options[causa.selectedIndex].getAttribute('data-tipo-choque');
    } else {
        document.getElementById('tipo_choque').value = '';
    }
}
document.getElementById('id_causa_accidente').onchange = actualizarTipoChoque;
actualizarTipoChoque();
</script>