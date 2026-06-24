<div class="mt-4 border-top pt-4">
    <h4>Reductor en mal estado</h4>
    <div class="row">
        <div class="col-md-4 mt-3">
            <label for="id_tipo_reductor">Tipo de reductor</label>
            <select class="form-control detalle-required" id="id_tipo_reductor" name="id_tipo_reductor">
                <option value="">Seleccione</option>
                <?php foreach ($catalogos['tipos_reductor'] as $reductor) { ?>
                    <option value="<?php echo $reductor['id_tipo_reductor']; ?>" <?php echo (isset($detalle['id_tipo_reductor']) && $detalle['id_tipo_reductor'] == $reductor['id_tipo_reductor']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($reductor['nombre_tipo_reductor']); ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="col-md-4 mt-3">
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