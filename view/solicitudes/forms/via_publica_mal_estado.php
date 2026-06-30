<div class="card shadow-sm mt-4">
    <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
        <i class="fa fa-hard-hat me-2"></i> Vía Pública en mal estado
    </div>
    <div class="card-body">
        <div class="row g-3">

            <div class="col-md-4">
                <label for="id_tipo_danio" class="form-label fw-semibold">Tipo de daño</label>
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