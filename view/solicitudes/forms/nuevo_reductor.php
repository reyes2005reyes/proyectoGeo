<div class="card shadow-sm mt-4">
    <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
        <i class="fa fa-plus-circle me-2"></i> Nuevo reductor
    </div>

    <div class="card-body">
        <div class="row g-3">

            <!-- Categoría -->
            <div class="col-md-6">
                <label for="id_categoria_reductor" class="form-label fw-semibold">
                    Categoría
                </label>

                <select class="form-select detalle-required categoria-reductor-filtro" name="id_categoria_reductor">

                    <option value="">Seleccione</option>

                    <?php foreach ($catalogos['categorias_reductor'] as $categoria) { ?>
                        <option
                            value="<?php echo $categoria['id_categoria_reductor']; ?>"
                            <?php echo (isset($detalle['id_categoria_reductor']) && $detalle['id_categoria_reductor'] == $categoria['id_categoria_reductor']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nombre_categoria']); ?>
                        </option>
                    <?php } ?>

                </select>
            </div>

            <!-- Tipo de reductor -->
            <div class="col-md-6">
                <label for="id_tipo_reductor" class="form-label fw-semibold">
                    Tipo de reductor
                </label>

                <select class="form-select detalle-required"
                        id="id_tipo_reductor"
                        name="id_tipo_reductor">

                    <option value="">Seleccione</option>

                    <?php foreach ($catalogos['tipos_reductor'] as $reductor) { ?>
                        <option
                            value="<?php echo $reductor['id_tipo_reductor']; ?>"
                            data-categoria="<?php echo $reductor['id_categoria_reductor']; ?>"
                            <?php echo (isset($detalle['id_tipo_reductor']) && $detalle['id_tipo_reductor'] == $reductor['id_tipo_reductor']) ? 'selected' : ''; ?>>

                            <?php echo htmlspecialchars($reductor['nombre_tipo_reductor']); ?>

                        </option>
                    <?php } ?>

                </select>
            </div>

        </div>
    </div>
</div>