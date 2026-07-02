<div class="container-fluid mt-4">

    <h1 class="mb-2">Actualizar Rol</h1>

    <p class="text-muted">
        Modifique la información y permisos del rol.
    </p>

    <?php while($rol = pg_fetch_assoc($roles)){ ?>

    <div class="card shadow-sm border-0">

        <div class="card-header text-white" style="background:#22314d;">
            Información del rol
        </div>

        <div class="card-body">
            <?php if (isset($_SESSION['error_rol'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo $_SESSION['error_rol']; unset($_SESSION['error_rol']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['exito_rol'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $_SESSION['exito_rol']; unset($_SESSION['exito_rol']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

<div class="container-fluid mt-4">

            <form action="<?php echo getUrl("roles","roles","postUpdate"); ?>" method="post">

                <div class="row">

                    <div class="col-md-4 mb-4">

                        <label for="rol_nombre" class="form-label">
                            Nombre
                        </label>

                        <input type="text"
                               class="form-control"
                               id="rol_nombre"
                               name="rol_nombre"
                               placeholder="Ingrese el nombre del rol"
                               value="<?php echo $rol['nombre_rol']; ?>">

                        <input type="hidden"
                               name="id_rol"
                               value="<?php echo $rol['id_rol']; ?>">

                    </div>

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>ACCION/MODULO</th>

                                <?php

                                $modulosArray = array();

                                while($modulo = pg_fetch_assoc($modulos)){

                                    echo "<th>".$modulo['nombre_modulo']."</th>";

                                    $modulosArray[] = $modulo;
                                }

                                ?>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            while($accion = pg_fetch_assoc($acciones)){

                                echo "<tr>";

                                echo "<td>".$accion['nombre_accion']."</td>";

                                foreach($modulosArray as $modulo){

                                    $checked = "";

                                    if(
                                        isset($permisos_rol[$modulo['id_modulo']]) &&
                                        in_array(
                                            $accion['id_accion'],
                                            $permisos_rol[$modulo['id_modulo']]
                                        )
                                    ){
                                        $checked = "checked";
                                    }

                                    echo "<td>";
                                    echo "<input type='checkbox' ";
                                    echo "name='permisos[".$modulo['id_modulo']."][".$accion['id_accion']."]' ";
                                    echo "value='1' ".$checked.">";
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
                    Actualizar rol
                </button>

            </form>

        </div>

    </div>

    <?php } ?>

</div>
```
