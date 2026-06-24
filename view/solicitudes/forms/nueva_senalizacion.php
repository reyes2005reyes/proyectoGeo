<div class="mt-4 border-top pt-4">
    <h4>Nueva senalizacion</h4>
    <div class="row">
        <div class="col-md-3 mt-3">
            <label for="id_tipo_senal">Tipo de senal</label>
            <select class="form-control detalle-required" id="id_tipo_senal" name="id_tipo_senal">
                <option value="">Seleccione</option>
                <?php foreach ($catalogos['tipos_senal'] as $senal) { ?>
                    <option value="<?php echo $senal['id_tipo_senal']; ?>" <?php echo (isset($detalle['id_tipo_senal']) && $detalle['id_tipo_senal'] == $senal['id_tipo_senal']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($senal['nombre_tipo_senal']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-3 mt-3">
            <label for="id_categoria">Categoria</label>
            <select class="form-control detalle-required" id="id_categoria" name="id_categoria">
                <option value="">Seleccione</option>
                <?php foreach ($catalogos['categorias'] as $categoria) { ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>" <?php echo (isset($detalle['id_categoria']) && $detalle['id_categoria'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-3 mt-3">
            <label for="id_orientacion">Orientacion</label>
            <select class="form-control detalle-required" id="id_orientacion" name="id_orientacion">
                <option value="">Seleccione</option>
                <?php foreach ($catalogos['orientaciones'] as $orientacion) { ?>
                    <option value="<?php echo $orientacion['id_orientacion']; ?>" <?php echo (isset($detalle['id_orientacion']) && $detalle['id_orientacion'] == $orientacion['id_orientacion']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($orientacion['nombre_orientacion']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </div>
</div>