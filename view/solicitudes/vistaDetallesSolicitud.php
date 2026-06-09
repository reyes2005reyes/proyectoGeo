<?php $idRol = $_SESSION['id_rol'] ?? null; ?>

<div class="container mt-4">

    <div class="card shadow-lg">

        <div class="card-header bg-black text-white">
            <h5 class="mb-0">
                Detalle de Solicitud #<?= htmlspecialchars($solicitud->getIdSolicitud() ?? 'N/A'); ?>
            </h5>
        </div>

        <div class="card-body">

            <?php
                $color = $solicitud->getColorEstado();
                $tipo = $solicitud->getNombreTipoSolicitud() ?? 'No existe dato';
                $detalle = $detalle ?? null;
            ?>

            <?php if ($tieneRespuesta): ?>
                <div class="alert alert-success">
                    ✔ Esta solicitud ya fue atendida por un funcionario.
                </div>
            <?php endif; ?>

            <div class="row g-4">

                <!-- USUARIO -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Usuario</label>
                    <div class="form-control">
                        <?= htmlspecialchars($solicitud->getNombreUsuario()); ?>
                    </div>
                </div>

                <!-- FECHA -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Fecha</label>
                    <div class="form-control">
                        <?= htmlspecialchars($solicitud->getFechaSolicitud()); ?>
                    </div>
                </div>

                <!-- TIPO -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tipo de Solicitud</label>
                    <span class="badge bg-info text-dark fs-6">
                        <?= htmlspecialchars($tipo); ?>
                    </span>
                </div>

                <!-- ESTADO -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Estado</label>
                    <span class="badge bg-<?= $color ?> fs-6">
                        <?= htmlspecialchars($solicitud->getNombreEstado()); ?>
                    </span>
                </div>

                <!-- DIRECCIÓN -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Dirección</label>
                    <div class="form-control">
                        <?= htmlspecialchars($solicitud->getDireccion()); ?>
                    </div>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Descripción</label>
                    <textarea class="form-control" rows="4" readonly><?= htmlspecialchars($solicitud->getDescripcion()); ?></textarea>
                </div>

                <!-- COORDENADAS -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Latitud</label>
                    <div class="form-control"><?= htmlspecialchars($solicitud->getLatitud()); ?></div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Longitud</label>
                    <div class="form-control"><?= htmlspecialchars($solicitud->getLongitud()); ?></div>
                </div>

                <!-- IMAGEN -->
                <div class="col-md-12 text-center">
                    <label class="form-label fw-bold">Imagen</label><br>

                    <?php if (!empty($solicitud->getImagen())): ?>
                        <img src="<?= htmlspecialchars($solicitud->getImagen()); ?>"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 300px;">
                    <?php else: ?>
                        <div class="border rounded p-4 bg-light text-muted">
                            No hay imagen disponible
                        </div>
                    <?php endif; ?>
                </div>

                <!-- DETALLES -->
                <div class="col-md-12 mt-4">
                    <hr>
                    <h5 class="text-primary">Detalles específicos</h5>
                </div>

                <?php if (empty($detalle)): ?>
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            No existen detalles específicos.
                        </div>
                    </div>
                <?php endif; ?>

                <!-- ========================= -->
                <!-- CAMBIO DE ESTADO (SOLO SI NO HAY RESPUESTA) -->
                <!-- ========================= -->

                <?php if ($idRol == 2 && !$tieneRespuesta): ?>
                    <div class="col-md-12 mt-4">
                        <hr>
                        <h5 class="text-warning">Gestión de Estado</h5>

                        <form method="POST"
                              action="<?= getUrl('solicitudes','Solicitudes','cambiarEstado') ?>">

                            <input type="hidden" name="id_solicitud"
                                   value="<?= $solicitud->getIdSolicitud(); ?>">

                            <div class="mb-3">
                                <label class="form-label">Nuevo estado</label>

                                <select name="id_estado" class="form-select" required>
                                    <option value="1">Pendiente</option>
                                    <option value="2">En revisión</option>
                                    <option value="3">En proceso</option>
                                    <option value="4">Rechazada</option>
                                    <option value="5">Completada</option>
                                </select>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-warning">
                                    Actualizar estado
                                </button>
                            </div>

                        </form>
                    </div>
                <?php endif; ?>

                <!-- RESPUESTA -->
                <div class="col-md-12 mt-4">
                    <hr>

                    <label class="form-label fw-bold">Respuesta</label>

                    <?php if ($tieneRespuesta): ?>

                        <div class="form-control bg-light mb-2">
                            <?= nl2br(htmlspecialchars($respuesta['mensaje'] ?? 'Sin respuesta')) ?>
                        </div>

                        <small class="text-muted d-block">
                            Respondido por:
                            <?= htmlspecialchars(
                                ($respuesta['primer_nombre'] ?? '') . ' ' . ($respuesta['primer_apellido'] ?? '')
                            ); ?>
                        </small>

                    <?php else: ?>

                        <form method="POST"
                              action="<?= getUrl('solicitudes','Solicitudes','responder') ?>">

                            <input type="hidden" name="id_solicitud"
                                   value="<?= $solicitud->getIdSolicitud(); ?>">

                            <textarea id="respuesta"
                                      name="mensaje"
                                      class="form-control"
                                      rows="4"
                                      maxlength="500"
                                      placeholder="Escribe tu respuesta aquí..."></textarea>

                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Máximo 500 caracteres</small>
                                <small id="contador" class="text-muted">0 / 500</small>
                            </div>

                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-success">
                                    Enviar respuesta
                                </button>
                            </div>

                        </form>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
const textarea = document.getElementById('respuesta');
const contador = document.getElementById('contador');

if (textarea && contador) {
    textarea.addEventListener('input', function () {
        let length = this.value.length;
        contador.textContent = length + " / 500";

        if (length >= 500) {
            this.value = this.value.substring(0, 500);
        }
    });
}
</script>