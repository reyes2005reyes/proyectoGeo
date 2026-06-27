<?php if (isset($_SESSION['solicitud_exitosa'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $_SESSION['solicitud_exitosa']; unset($_SESSION['solicitud_exitosa']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Solicitudes</h2>
            <p class="text-muted mb-0">Listado de solicitudes registradas</p>
        </div>
    </div>
    <!-- Filtro por rango de fecha -->
    <form method="GET" action="index.php" class="card shadow-sm mb-4">
        <input type="hidden" name="modulo" value="solicitudes">
        <input type="hidden" name="controlador" value="solicitudes">
        <input type="hidden" name="funcion" value="listar">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control" max="<?php echo date('Y-m-d'); ?>"
                        value="<?php echo isset($_GET['fecha_inicio']) ? htmlspecialchars($_GET['fecha_inicio']) : ''; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha fin</label>
                    <input type="date" name="fecha_fin" class="form-control" max="<?php echo date('Y-m-d'); ?>"
                        value="<?php echo isset($_GET['fecha_fin']) ? htmlspecialchars($_GET['fecha_fin']) : ''; ?>">
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn text-white w-100" style="background-color: #1a2942;">
                        <i class="fa fa-search me-1"></i> Filtrar
                    </button>
                    <a href="<?php echo getUrl('solicitudes','solicitudes','listar'); ?>" class="btn btn-outline-secondary w-100">
                        <i class="fa fa-times me-1"></i> Limpiar
                    </a>
                </div>
            </div>
        </div>
    </form>
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
                                        <?php
                                            $colores = array(
                                                'Pendiente'   => 'bg-warning text-dark',
                                                'En revisión' => 'bg-info text-dark',
                                                'En proceso'  => 'bg-primary',
                                                'Rechazada' => 'bg-danger',
                                                'Completada'  => 'bg-success',
                                            );
                                            $estado = $solicitud['nombre_estado_solicitud'];
                                            $clase  = isset($colores[$estado]) ? $colores[$estado] : 'bg-secondary';
                                        ?>
                                        <span class="badge <?php echo $clase; ?>">
                                            <?php echo htmlspecialchars($estado); ?>
                                        </span>
                                </td>
                                <td><?php echo htmlspecialchars($solicitud['direccion']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                                <td><?php echo htmlspecialchars($solicitud['primer_nombre'] . ' ' . $solicitud['primer_apellido']); ?></td>
                                <td>
                                    <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 3) { ?>
                                    <a class="btn btn-sm text-white" style="background-color: #1a2942;"
                                       href="<?php echo getUrl('solicitudes','solicitudes','getShow', array('id_solicitud' => $solicitud['id_solicitud'])); ?>">
                                        <i class="fa fa-eye me-1"></i> Ver
                                    </a>
                                    <?php } ?>
                                    <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 2) { ?>
                                        <a class="btn btn-sm btn-outline-secondary"
                                           href="<?php echo getUrl('solicitudes','solicitudes','getShow', array('id_solicitud' => $solicitud['id_solicitud'])); ?>">
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