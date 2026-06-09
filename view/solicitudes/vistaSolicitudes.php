<div class="card shadow-sm">

        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Listado de Solicitudes</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover table-striped align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>Nombre Usuario</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Atendida</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php if (!empty($solicitudes)) { ?>

                            <?php foreach ($solicitudes as $s) { ?>

                                <?php
                                    $color = $s->getColorEstado();

                                    // ✔ NUEVA LÓGICA
                                    $yaRespondida = $s->getTieneRespuesta();
                                    //$yaRespondida = !empty($s->getIdUsuarioResponde());
                                ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($s->getNombreUsuario()); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($s->getFechaSolicitud()); ?>
                                    </td>

                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= htmlspecialchars($s->getNombreTipoSolicitud()); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge bg-<?= $color; ?>">
                                            <?= htmlspecialchars($s->getNombreEstado()); ?>
                                        </span>
                                    </td>

                                    
                                    <td>
                                        <?php if ($yaRespondida) { ?>
                                            <span class="badge bg-success">
                                                ✔ Atendida
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-warning text-dark">
                                                Pendiente
                                            </span>
                                        <?php } ?>
                                    </td>

                                    <td class="text-center">

                                        <a href="<?= getUrl('solicitudes', 'Solicitudes', 'ver', ['id' => $s->getIdSolicitud()]) ?>"
                                           class="btn btn-outline-primary btn-sm">

                                            <i class="fas fa-eye"></i>
                                            Ver Detalle
                                        </a>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No hay solicitudes disponibles.
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>