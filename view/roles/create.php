<div class="mt-5"> 
    <h1 class="display-4">Registro Rol</h1>
</div>
<div class="mt-5">
    <form action="<?php echo getUrl("roles", "roles", "postCreate");?>" method="post">

        <div class="row">
            <div class="col-4 mt-3">
                <label for="rol_nombre">Nombre</label>
                <input type="text" class="form-control" id="rol_nombre" name="rol_nombre">            
            </div>
        </div>
        <div class="mt-5">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th> Accion/Modulo</th>
                        <?php 
                            $modulosArray = [];

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
                            echo "<td>{$accion['nombre_accion']}</td>";

                            foreach($modulosArray as $modulo){
                                echo "<td><input type='checkbox' name='permisos[".$modulo['id_modulo']."][".$accion['id_accion']."]' value='1'></td>";
                            }

                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-4">
            <input type="submit" class="btn btn-success mt-4" value="Registrar Rol">
        </div>
    </form>
</div>
