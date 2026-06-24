<div class="mt-4 border-top pt-4">
    <h4>Reporte de accidente</h4>

    <div class="row">

        <div class="col-md-6 mt-3">
            <label for="id_tipo_vehiculo">Tipo de vehículo</label>
            <select class="form-control detalle-required" id="id_tipo_vehiculo" name="id_tipo_vehiculo">
                <option value="">Seleccione</option>

                <?php foreach ($catalogos['tipos_vehiculo'] as $vehiculo) { ?>
                    <option value="<?php echo $vehiculo['id_tipo_vehiculo']; ?>"
                        <?php echo (isset($detalle['id_tipo_vehiculo']) && $detalle['id_tipo_vehiculo'] == $vehiculo['id_tipo_vehiculo']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vehiculo['nombre_vehiculo']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-6 mt-3">
            <label for="id_causa_accidente">Causa</label>
            <select class="form-control detalle-required" id="id_causa_accidente" name="id_causa_accidente">
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

    </div>

    <div class="row">

        <div class="col-md-6 mt-3">
            <label for="tipo_choque">Tipo de choque</label>
            <input type="text"
                   class="form-control"
                   id="tipo_choque"
                   readonly>
        </div>

        <div class="col-md-6 mt-3">
            <label for="numero_lesionados">Cantidad de lesionados</label>
            <input type="number"
                   min="0"
                   class="form-control"
                   id="numero_lesionados"
                   name="numero_lesionados"
                   value="<?php echo isset($detalle['numero_lesionados']) ? $detalle['numero_lesionados'] : 0; ?>">
        </div>

    </div>

</div>

<script>
function actualizarTipoChoque() {

    var causa = document.getElementById('id_causa_accidente');

    if (causa.selectedIndex > 0) {

        var opcion = causa.options[causa.selectedIndex];

        document.getElementById('tipo_choque').value =
            opcion.getAttribute('data-tipo-choque');

    } else {

        document.getElementById('tipo_choque').value = '';
    }
}

document.getElementById('id_causa_accidente')
        .onchange = actualizarTipoChoque;

actualizarTipoChoque();
</script>