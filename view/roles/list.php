<div class="container-fluid mt-4">

    <h1 class="mb-2">Listado de Roles</h1>

    <p class="text-muted">
        Gestión de roles registrados en el sistema
    </p>

    <div class="card shadow-sm border-0">

        <div class="card-header text-white" style="background:#22314d;">
            Roles registrados
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        while ($rol = pg_fetch_assoc($roles)) {
                        ?>

                            <tr>

                                <td>
                                    <?php echo $rol['id_rol']; ?>
                                </td>

                                <td>
                                    <?php echo $rol['nombre_rol']; ?>
                                </td>

                                <td>

                                    <a href="<?php echo getUrl(
                                        "roles",
                                        "roles",
                                        "getUpdate",
                                        array(
                                            "id_rol" => $rol['id_rol']
                                        )
                                    ); ?>">

                                        <button type="button"
                                                class="btn btn-primary btn-sm">
                                            Editar
                                        </button>

                                    </a>

                                </td>

                            </tr>

                        <?php
                        }
                        ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>