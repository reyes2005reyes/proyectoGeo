<div class="mt-4 border-top pt-4">
    <h4>Via publica en mal estado</h4>
    <div class="row">
        <div class="col-md-4 mt-3">
            <label for="id_tipo_danio">Tipo de danio</label>
            <select class="form-control detalle-required" id="id_tipo_danio" name="id_tipo_danio">
                <option value="">Seleccione</option>
                <?php foreach ($catalogos['tipos_danio'] as $danio) { ?>
                    <option value="<?php echo $danio['id_tipo_danio']; ?>" <?php echo (isset($detalle['id_tipo_danio']) && $detalle['id_tipo_danio'] == $danio['id_tipo_danio']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($danio['nombre_tipo_danio']); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>