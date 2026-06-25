<div class="container py-4">
    <div class="mb-4">
        <h2 class="fw-bold">Nueva PQRSF</h2>
        <p class="text-muted">Petición, Queja, Reclamo, Sugerencia o Felicitación</p>
    </div>

    <form action="<?php echo getUrl('solicitudes', 'pqrsf', 'postCreate'); ?>" method="post" id="formPqrsf">

        <input type="hidden" name="id_estado_solicitud" value="1">

        <div class="card shadow-sm mb-4">
            <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
                <i class="fa fa-envelope me-2"></i> PQRSF
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="id_tipo_pqrsf" class="form-label fw-semibold">Tipo PQRSF</label>
                        <select class="form-select" id="id_tipo_pqrsf" name="id_tipo_pqrsf" required>
                            <option value="">Seleccione</option>
                            <?php foreach ($catalogos['tipos_pqrsf'] as $pqrsf) { ?>
                                <option value="<?php echo $pqrsf['id_tipo_pqrsf']; ?>"
                                    <?php echo (isset($detalle['id_tipo_pqrsf']) && $detalle['id_tipo_pqrsf'] == $pqrsf['id_tipo_pqrsf']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($pqrsf['tipo_pqrsf']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5"
                                  placeholder="Describa detalladamente su solicitud..." required></textarea>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn text-white px-4" style="background-color: #1a2942;">
                <i class="fa fa-save me-1"></i> Registrar
            </button>
            <a class="btn btn-secondary px-4" href="<?php echo getUrl('solicitudes', 'pqrsf', 'listar', false); ?>">
                <i class="fa fa-arrow-left me-1"></i> Volver
            </a>
        </div>

    </form>
</div>