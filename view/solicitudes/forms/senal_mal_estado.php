<div class="card shadow-sm mt-4">
    <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
        <i class="fa fa-exclamation-triangle me-2"></i> Señal en Mal Estado
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-3">
                <label for="id_tipo_senal" class="form-label fw-semibold">Tipo de Señal</label>
                <select class="form-select detalle-required" id="id_tipo_senal" name="id_tipo_senal">
                    <option value="">Seleccione</option>
                    <?php foreach ($catalogos['tipos_senal'] as $senal) { ?>
                        <option value="<?php echo $senal['id_tipo_senal']; ?>"
                            <?php echo (isset($detalle['id_tipo_senal']) && $detalle['id_tipo_senal'] == $senal['id_tipo_senal']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($senal['nombre_tipo_senal']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="id_categoria" class="form-label fw-semibold">Categoría</label>
                <select class="form-select detalle-required" id="id_categoria" name="id_categoria">
                    <option value="">Seleccione</option>
                    <?php foreach ($catalogos['categorias'] as $categoria) { ?>
                        <option value="<?php echo $categoria['id_categoria']; ?>"
                            <?php echo (isset($detalle['id_categoria']) && $detalle['id_categoria'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="id_orientacion" class="form-label fw-semibold">Orientación</label>
                <select class="form-select detalle-required" id="id_orientacion" name="id_orientacion">
                    <option value="">Seleccione</option>
                    <?php foreach ($catalogos['orientaciones'] as $orientacion) { ?>
                        <option value="<?php echo $orientacion['id_orientacion']; ?>"
                            <?php echo (isset($detalle['id_orientacion']) && $detalle['id_orientacion'] == $orientacion['id_orientacion']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($orientacion['nombre_orientacion']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="id_tipo_danio" class="form-label fw-semibold">Tipo de Daño</label>
                <select class="form-select detalle-required" id="id_tipo_danio" name="id_tipo_danio">
                    <option value="">Seleccione</option>
                    <?php foreach ($catalogos['tipos_danio'] as $danio) { ?>
                        <option value="<?php echo $danio['id_tipo_danio']; ?>"
                            <?php echo (isset($detalle['id_tipo_danio']) && $detalle['id_tipo_danio'] == $danio['id_tipo_danio']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($danio['nombre_tipo_danio']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

        </div>
    </div>
</div>