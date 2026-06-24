<div class="mt-4 border-top pt-4">
    <h4>PQRSF</h4>
    <div class="row">
        <div class="col-md-4 mt-3">
            <label for="id_tipo_pqrsf">Tipo PQRSF</label>
            <select class="form-control detalle-required" id="id_tipo_pqrsf" name="id_tipo_pqrsf">
                <option value="">Seleccione</option>
                <?php foreach ($catalogos['tipos_pqrsf'] as $pqrsf) { ?>
                    <option value="<?php echo $pqrsf['id_tipo_pqrsf']; ?>" <?php echo (isset($detalle['id_tipo_pqrsf']) && $detalle['id_tipo_pqrsf'] == $pqrsf['id_tipo_pqrsf']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($pqrsf['tipo_pqrsf']); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>