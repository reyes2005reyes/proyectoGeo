<div class="mt-5"> 
    <h1 class="display-4">Actualizar Rol</h1>
</div>

<div class="mt-5">

<?php while($rol = pg_fetch_assoc($roles)){ ?>

<form action="<?php echo getUrl("roles","roles","postUpdate"); ?>" method="post">

    <div class="row">
        <div class="col-4 mt-3">
            <label for="rol_nombre">Nombre</label>

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

    <div class="mt-5">

        <table class="table table-striped table-hover">

            <thead>
                <tr>
                    <th>Accion/Modulo</th>

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

    <div class="col-4">
        <input type="submit"
               class="btn btn-success mt-4"
               value="Actualizar Rol">
    </div>

</form>

<?php } ?>

</div>