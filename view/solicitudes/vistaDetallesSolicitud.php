<?php
$idRol = $_SESSION['id_rol'] ?? null;
$estado = $solicitud->getIdEstadoSolicitud();
?>

<div class="container mt-4">

    <div class="card shadow-lg">

        <div class="card-header bg-black text-white">
            <h5 class="mb-0">
                Detalle de Solicitud #<?= htmlspecialchars($solicitud->getIdSolicitud()); ?>
            </h5>
        </div>

        <div class="card-body">

            <?php
                $color = $solicitud->getColorEstado();
                $tipo = $solicitud->getNombreTipoSolicitud();
            ?>

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
                    <label class="form-label fw-bold">Tipo</label>
                    <span class="badge bg-info text-dark">
                        <?= htmlspecialchars($tipo); ?>
                    </span>
                </div>

                <!-- ESTADO -->
                <div class="col-md-6">
                    <label class="form-label fw-bold">Estado</label>
                    <span class="badge bg-<?= $color ?>">
                        <?= htmlspecialchars($solicitud->getNombreEstado()); ?>
                    </span>
                </div>

                <!-- DESCRIPCIÓN -->
                <div class="col-md-12">
                    <label class="form-label fw-bold">Descripción de la Solicitud</label>
                    <textarea class="form-control" rows="4" readonly><?= htmlspecialchars($solicitud->getDescripcion()); ?></textarea>
                </div>

                <!-- ESTADOS BLOQUEADOS -->
                <?php if ($estado == 4): ?>
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-danger">
                            ❌ Esta solicitud ya fue rechazada.
                        </div>
                    </div>
                <?php elseif ($estado == 5): ?>
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-success">
                            ✔ Esta solicitud ya fue completada.
                        </div>
                    </div>
                <?php endif; ?>

                <!-- GESTIÓN -->
                <?php if ($idRol == 2 && !in_array($estado, [4,5])): ?>

                <div class="col-md-12 mt-4">
                    <hr>
                    <h5 class="text-warning">Gestión de solicitud</h5>

                    <form method="POST"
                          action="<?= getUrl('solicitudes','Solicitudes','actualizarSolicitud') ?>">

                        <input
                            type="hidden"
                            name="id_solicitud"
                            value="<?= htmlspecialchars($solicitud->getIdSolicitud()); ?>"
                        >

                        <div class="mb-3">
                            <label class="form-label">Nuevo estado</label>

                            <select name="id_estado" class="form-select" required>
                                <option value="">Seleccione...</option>

                                <?php if ($estado == 1): ?>
                                    <option value="2">En revisión</option>
                                    <option value="4">Rechazada</option>

                                <?php elseif ($estado == 2): ?>
                                    <option value="3">En proceso</option>
                                    <option value="4">Rechazada</option>

                                <?php elseif ($estado == 3): ?>
                                    <option value="5">Completada</option>
                                    <option value="4">Rechazada</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Mensaje (respuesta / justificación)
                            </label>

                            <textarea
                                name="mensaje"
                                id="mensaje"
                                class="form-control"
                                rows="4"
                                maxlength="250"
                                placeholder="Escribe la respuesta o justificación del cambio..."
                                required></textarea>

                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">
                                    Este campo es obligatorio.
                                </small>

                                <small id="contadorMensaje" class="text-secondary">
                                    Quedan 250 caracteres
                                </small>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            Estado actual de la solicitud:
                            <strong><?= htmlspecialchars($solicitud->getNombreEstado()); ?></strong>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning">
                                Actualizar
                            </button>
                        </div>

                    </form>
                </div>

                <?php endif; ?>

                <!-- AUDITORÍA -->
                <?php if (!empty($auditorias)): ?>

                    <div class="col-md-12 mt-4">
                        <hr>

                        <label class="form-label fw-bold">
                            Auditoría de seguimiento
                        </label>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Funcionario</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Respuesta / Justificación</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($auditorias as $item): ?>
                                        <tr>
                                            <td>
                                                <?= htmlspecialchars($item['nombre_funcionario'] ?? 'Sistema'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($item['estado'] ?? 'Sin estado'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($item['fecha']); ?>
                                            </td>

                                            <td>
                                                <?= nl2br(htmlspecialchars($item['mensaje'])); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>