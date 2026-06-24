<div class="mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-4 mb-0">Detalle Solicitud</h1>

        <a class="btn btn-secondary"
           href="<?php echo getUrl('solicitudes', 'solicitudes', 'listar', false); ?>">
            Volver
        </a>
    </div>

    <table class="table table-bordered">
        <tbody>

            <tr>
                <th>ID</th>
                <td><?php echo htmlspecialchars($solicitud['id_solicitud']); ?></td>
            </tr>

            <tr>
                <th>Tipo</th>
                <td><?php echo htmlspecialchars($solicitud['nombre_tipo_solicitud']); ?></td>
            </tr>

            <tr>
                <th>Estado</th>
                <td><?php echo htmlspecialchars($solicitud['nombre_estado_solicitud']); ?></td>
            </tr>

            <tr>
                <th>Usuario</th>
                <td>
                    <?php
                    echo htmlspecialchars(
                        $solicitud['primer_nombre'] . ' ' .
                        $solicitud['primer_apellido']
                    );
                    ?>
                </td>
            </tr>

            <tr>
                <th>Dirección</th>
                <td><?php echo htmlspecialchars($solicitud['direccion']); ?></td>
            </tr>

            <tr>
                <th>Coordenada X</th>
                <td><?php echo htmlspecialchars($solicitud['coord_x']); ?></td>
            </tr>

            <tr>
                <th>Coordenada Y</th>
                <td><?php echo htmlspecialchars($solicitud['coord_y']); ?></td>
            </tr>

            <tr>
                <th>Imagen</th>
                <td>
                    <?php if (!empty($solicitud['imagen_url'])) { ?>

                        <img
                            src="<?php echo htmlspecialchars($solicitud['imagen_url']); ?>"
                            alt="Imagen de la solicitud"
                            class="img-fluid"
                            style="max-width:300px;">

                    <?php } else { ?>

                        Sin imagen

                    <?php } ?>
                </td>
            </tr>

            <tr>
                <th>Fecha</th>
                <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
            </tr>

            <tr>
                <th>Descripción</th>
                <td>
                    <?php echo nl2br(htmlspecialchars($solicitud['descripcion'])); ?>
                </td>
            </tr>

        </tbody>
    </table>
</div>

<?php if ($_SESSION['id_rol'] == 2 || $_SESSION['id_rol'] == 1) { ?>

    <div class="card mt-4">
        <div class="card-header">
            Responder Solicitud
        </div>

        <div class="card-body">

            <form action="<?php echo getUrl('solicitudes','solicitudes','postResponder'); ?>" method="post">

                <input type="hidden"
                       name="id_solicitud"
                       value="<?php echo $solicitud['id_solicitud']; ?>">

                <div class="mb-3">
                    <label for="id_estado_solicitud">
                        Nuevo Estado
                    </label>

                    <select
                        class="form-control"
                        id="id_estado_solicitud"
                        name="id_estado_solicitud"
                        required>

                        <?php while($estado = pg_fetch_assoc($estados)) { ?>

                            <option value="<?php echo $estado['id_estado_solicitud']; ?>">
                                <?php echo htmlspecialchars($estado['nombre_estado_solicitud']); ?>
                            </option>

                        <?php } ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label for="mensaje">
                        Respuesta
                    </label>

                    <textarea
                        class="form-control"
                        id="mensaje"
                        name="mensaje"
                        rows="4"
                        required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">
                    Guardar respuesta
                </button>

            </form>

        </div>
    </div>

<?php } ?>

<div class="card mt-4">
    <div class="card-header">
        Historial de Respuestas
    </div>

    <div class="card-body">

        <?php if (pg_num_rows($respuestas) > 0) { ?>

            <?php while($respuesta = pg_fetch_assoc($respuestas)) { ?>

                <div class="border rounded p-3 mb-3">

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $respuesta['primer_nombre'] . ' ' .
                            $respuesta['primer_apellido']
                        );
                        ?>
                    </strong>

                    <br>

                    Estado:
                    <?php echo htmlspecialchars($respuesta['nombre_estado_solicitud']); ?>

                    <br>

                    Fecha:
                    <?php echo htmlspecialchars($respuesta['fecha']); ?>

                    <hr>

                    <?php echo nl2br(htmlspecialchars($respuesta['mensaje'])); ?>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p>No hay respuestas registradas.</p>

        <?php } ?>

    </div>
</div>