<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Solicitudes</h2>
            <p class="text-muted mb-0">Listado de solicitudes registradas</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header text-white fw-semibold" style="background-color: #1a2942;">
            <i class="fa fa-list me-2"></i> Solicitudes
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead style="background-color: #1a2942; color: white;">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Dirección</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (pg_num_rows($solicitudes) > 0) { ?>
                        <?php while ($solicitud = pg_fetch_assoc($solicitudes)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($solicitud['id_solicitud']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['nombre_tipo_solicitud']); ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($solicitud['nombre_estado_solicitud']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($solicitud['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['primer_nombre'] . ' ' . $solicitud['primer_apellido']); ?></td>
                                <td>
                                    <a class="btn btn-sm text-white" style="background-color: #1a2942;"
                                       href="<?php echo getUrl('solicitudes','solicitudes','getShow', array('id_solicitud' => $solicitud['id_solicitud'])); ?>">
                                        <i class="fa fa-eye me-1"></i> Ver
                                    </a>
                                    <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) { ?>
                                        <a class="btn btn-sm btn-outline-secondary"
                                           href="<?php echo getUrl('solicitudes','solicitudes','getResponder', array('id_solicitud' => $solicitud['id_solicitud'])); ?>">
                                            <i class="fa fa-reply me-1"></i> Responder
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa fa-inbox fa-2x d-block mb-2"></i>
                                No hay solicitudes registradas
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>