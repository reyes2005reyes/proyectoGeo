<div class="container-fluid mt-4">

    <h1 class="mb-2">Registro rol</h1>

    <p class="text-muted">
        Complete los campos para registrar un nuevo rol.
    </p>

    <div class="card shadow-sm border-0">

        <div class="card-header text-white" style="background:#22314d;">
            Información del rol
        </div>

        <div class="card-body">

            <form action="<?php echo getUrl("roles", "roles", "postCreate"); ?>" method="post">

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label for="rol_nombre" class="form-label">Nombre</label>
                        <input type="text"
                               class="form-control"
                               id="rol_nombre"
                               name="rol_nombre">
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>ACCION/MODULO</th>

                                <?php
                                    $modulosArray = array();

                                    while ($modulo = pg_fetch_assoc($modulos)) {

                                        echo "<th>" . $modulo['nombre_modulo'] . "</th>";

                                        $modulosArray[] = $modulo;
                                    }
                                ?>
                            </tr>
                        </thead>

                        <tbody>

                            <?php

                                while ($accion = pg_fetch_assoc($acciones)) {

                                    echo "<tr>";

                                    echo "<td>" . $accion['nombre_accion'] . "</td>";

                                    foreach ($modulosArray as $modulo) {

                                        echo "<td>";
                                        echo "<input type='checkbox' ";
                                        echo "name='permisos[" . $modulo['id_modulo'] . "][" . $accion['id_accion'] . "]' ";
                                        echo "value='1'>";
                                        echo "</td>";
                                    }

                                    echo "</tr>";
                                }

                            ?>

                        </tbody>

                    </table>

                </div>

                <button type="submit"
                        class="btn btn-success mt-3">
                    Registrar rol
                </button>

            </form>

        </div>

    </div>

</div>
```
