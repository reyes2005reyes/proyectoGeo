<div class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4 mb-0">Solicitudes</h1>
        <a class="btn btn-success" href="<?php echo getUrl('solicitudes', 'solicitudes', 'getCreate', false); ?>">
            Nueva Solicitud
        </a>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Direccion</th>
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
                        <td><?php echo htmlspecialchars($solicitud['nombre_estado_solicitud']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['primer_nombre'] . ' ' . $solicitud['primer_apellido']); ?></td>
                        <td>
                            <a class="btn btn-info btn-sm" href="<?php echo getUrl('solicitudes','solicitudes','getShow', array('id_solicitud' => $solicitud['id_solicitud'])); ?>">
                                Ver
                            </a>
                            <a class="btn btn-primary btn-sm" href="<?php echo getUrl('solicitudes','solicitudes','getShow', array('id_solicitud' => $solicitud['id_solicitud'])); ?>">
                                Responder
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7" class="text-center">No hay solicitudes registradas</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
