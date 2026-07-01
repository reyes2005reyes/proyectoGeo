<div class="card shadow-sm mt-4">
    <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
        <i class="fa fa-sign me-2"></i> Nueva señalización
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Tipo de señal -->
            <div class="col-md-4">
                <label for="id_tipo_senal" class="form-label fw-semibold">Tipo de señal</label>
                <select class="form-select tipo-senal-filtro">
                    <option value="">Seleccione</option>

                    <?php foreach ($catalogos['tipos_senal'] as $tipo) { ?>
                        <option value="<?php echo $tipo['id_tipo_senal']; ?>">
                            <?php echo htmlspecialchars($tipo['nombre_tipo_senal']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Categoría -->
            <div class="col-md-4">
                <label for="id_categoria" class="form-label fw-semibold">Categoría</label>
                <select class="form-select categoria-filtro">
                    <option value="">Seleccione</option>

                    <?php foreach ($catalogos['categorias'] as $categoria) { ?>
                        <option
                            value="<?php echo $categoria['id_categoria']; ?>"
                            data-tipo="<?php echo $categoria['id_tipo_senal']; ?>">
                            <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Señal -->
            <div class="col-md-4">
                <label for="id_senal" class="form-label fw-semibold">Señal</label>
                <select class="form-select detalle-required" id="id_senal" name="id_senal">
                    <option value="">Seleccione</option>

                    <?php foreach ($catalogos['senales'] as $senal) { ?>
                        <option
                            value="<?php echo $senal['id_senal']; ?>"
                            data-categoria="<?php echo $senal['id_categoria']; ?>"
                            data-descripcion="<?php echo htmlspecialchars($senal['descripcion']); ?>">
                            <?php echo $senal['codigo'] . ' - ' . $senal['nombre_senal']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <!-- Orientación -->
            <div class="col-md-4">
                <label for="id_orientacion" class="form-label fw-semibold">Orientación</label>
                <select class="form-select detalle-required" id="id_orientacion" name="id_orientacion">
                    <option value="">Seleccione</option>

                    <?php foreach ($catalogos['orientaciones'] as $orientacion) { ?>
                        <option
                            value="<?php echo $orientacion['id_orientacion']; ?>"
                            <?php echo (isset($detalle['id_orientacion']) && $detalle['id_orientacion'] == $orientacion['id_orientacion']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($orientacion['nombre_orientacion']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold">Descripción de la señal</label>

                <textarea
                    id="descripcion_senal"
                    class="form-control descripcion-senal"
                    rows="3"
                    readonly></textarea>
            </div>

        </div>
    </div>
</div>
