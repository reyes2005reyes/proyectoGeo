<div class="mt-5">
    <h1 class="display-4">Listado de Roles</h1>
</div>
<div class="mt-5">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($rol = pg_fetch_assoc($roles)) {
                echo "<tr>";
                echo "<td>" . $rol['id_rol'] . "</td>";
                echo "<td>" . $rol['nombre_rol'] . "</td>";
                echo "<td><a href='" . getUrl("roles", "roles", "getUpdate", array("id_rol" => $rol['id_rol'])) . "'><button class='btn btn-primary'>Editar</button></a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
