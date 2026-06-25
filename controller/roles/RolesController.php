<?php

include_once '../model/MasterModel.php';

class RolesController {

    public function getCreate() {

        $obj = new MasterModel();

        $sql = "SELECT * FROM modulos";
        $modulos = $obj->select($sql);

        $sql = "SELECT * FROM acciones";
        $acciones = $obj->select($sql);

        include_once '../view/roles/create.php';
    }

    public function postCreate() {

        $obj = new MasterModel();

        $rol_nombre = trim($_POST['rol_nombre']);

        if (strlen($rol_nombre) < 3) {

            echo "<script>
                alert('El nombre del rol debe tener mínimo 3 caracteres');
                history.back();
              </script>";
            exit();
        }

        $sql = "SELECT * FROM roles WHERE LOWER(nombre_rol) = LOWER('$rol_nombre')";
 
        $validar = $obj->select($sql);

        if (pg_num_rows($validar) > 0) {

            echo "<script>
                alert('Ya existe un rol con ese nombre');
                history.back();
              </script>";
            exit();
        }

        $rol_id = $obj->autoincrement("roles", "id_rol");

        $sql = "INSERT INTO roles (id_rol, nombre_rol) VALUES($rol_id, '$rol_nombre')";
        $obj->insert($sql);

        if (isset($_POST['permisos'])) {
            $permisos = $_POST['permisos'];
        } else {
            $permisos = array();
        }

        foreach ($permisos as $modulo_id => $acciones) {

        foreach ($acciones as $accion_id => $valor) {

            $per_id = $obj->autoincrement("permisos", "id_permiso");

            $sql = "INSERT INTO permisos
                    (id_permiso, id_rol, id_modulo, id_accion)
                    VALUES($per_id, $rol_id, $modulo_id, $accion_id)";

            $obj->insert($sql);
            }
        }

        echo "<script>
                alert('Rol registrado correctamente');
          </script>";

        redirect(getUrl("roles", "roles", "getRoles"));
    }

    public function getRoles() {

        $obj = new MasterModel();

        $sql = "SELECT * FROM roles";
        $roles = $obj->select($sql);

        include_once '../view/roles/list.php';
    }

    public function getUpdate() {

        $obj = new MasterModel();

        $id_rol = $_GET['id_rol'];

        $sql = "SELECT * FROM roles WHERE id_rol = $id_rol";
        $roles = $obj->select($sql);

        $sql = "SELECT * FROM modulos";
        $modulos = $obj->select($sql);

        $sql = "SELECT * FROM acciones";
        $acciones = $obj->select($sql);

        $sql = "SELECT * FROM permisos WHERE id_rol = $id_rol";
        $permisos = $obj->select($sql);

        $permisos_rol = array();

        while ($perm = pg_fetch_assoc($permisos)) {

            if (!isset($permisos_rol[$perm['id_modulo']])) {
                $permisos_rol[$perm['id_modulo']] = array();
            }

            $permisos_rol[$perm['id_modulo']][] = $perm['id_accion'];
        }

        include_once '../view/roles/update.php';
    }


    public function postUpdate() {

        $obj = new MasterModel();

        $id_rol = $_POST['id_rol'];
        $rol_nombre = trim($_POST['rol_nombre']);

        if (strlen($rol_nombre) < 3) {

            echo "<script>
                    alert('El nombre del rol debe tener mínimo 3 caracteres');
                    history.back();
                </script>";
            exit();
        }

        $sql = "SELECT * FROM roles WHERE LOWER(nombre_rol) = LOWER('$rol_nombre') AND id_rol <> $id_rol";

        $validar = $obj->select($sql);

        if (pg_num_rows($validar) > 0) {

            echo "<script>
                    alert('Ya existe un rol con ese nombre');
                    history.back();
                </script>";
            exit();
        }

        $sql = "UPDATE roles SET nombre_rol = '$rol_nombre' WHERE id_rol = $id_rol";

        $obj->update($sql);

        if (isset($_POST['permisos'])) {
            $permisos = $_POST['permisos'];
        } else {
            $permisos = array();
        }

        $sql = "DELETE FROM permisos WHERE id_rol = $id_rol";
        $obj->delete($sql);

        foreach ($permisos as $modulo_id => $acciones) {

            foreach ($acciones as $accion_id => $valor) {

                $per_id = $obj->autoincrement("permisos", "id_permiso");

                $sql = "INSERT INTO permisos (id_permiso, id_rol, id_modulo, id_accion) VALUES($per_id, $id_rol, $modulo_id, $accion_id)";

                $obj->insert($sql);
            }
        }

        echo "<script>
                alert('Rol actualizado correctamente');
            </script>";

        redirect(getUrl("roles", "roles", "getRoles"));
    }
}
?>