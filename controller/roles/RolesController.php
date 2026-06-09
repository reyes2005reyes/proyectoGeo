<?php

include_once '../model/MasterModel.php';

class RolesController{

    public function getCreate(){

    $obj = new MasterModel();

    $sql = "SELECT * FROM modulos";
    $modulos = $obj->select($sql);

    $sql = "SELECT * FROM acciones";
    $acciones = $obj->select($sql);

    include_once '../view/roles/create.php';
    }

    public function postCreate(){
        $obj = new MasterModel();
        $rol_nombre = $_POST['rol_nombre'];
        $rol_id = $obj ->autoincrement("roles", "id_rol");

        $sql = "INSERT INTO roles VALUES($rol_id, '$rol_nombre')";
        $obj->insert($sql);

        $permisos = $_POST['permisos'];
        dd($permisos);

        $permisosFormateados = [];
        foreach ($permisos as $modulo_id => $acciones) {
            foreach ($acciones as $accion_id => $valor) {
                // $permisosFormateados[$modulo_id][] = $accion_id;
                $per_id = $obj->autoincrement("permisos", "id_permiso");
                $sql = "INSERT INTO permisos VALUES($per_id, $rol_id, $modulo_id, $accion_id)";
                
                $obj->insert($sql);
            }
        }

        redirect(getUrl("roles", "roles", "getRoles"));
    }

    public function getRoles(){
        $obj = new MasterModel();
        $sql = "SELECT * FROM roles";
        $roles = $obj->select($sql);

        include_once '../view/roles/list.php';
    }

    public function getUpdate(){
        $obj = new MasterModel();

        $id_rol = $_GET['id_rol'];

        $sql = "SELECT * FROM roles WHERE id_rol = $id_rol";
        $roles = $obj->select($sql);

        $sql = "SELECT * FROM modulos";
        $modulos = $obj->select($sql);

        $sql = "SELECT * FROM acciones";
        $acciones = $obj->select($sql);

        $sql = "SELECT * FROM permisos WHERE rol_id = $id_rol";
        $permisos = $obj->select($sql);

        $permisos_rol = [];
        while($perm = pg_fetch_assoc($permisos)){
            $permisos_rol[$perm['id_modulo']][] = $perm['id_accion'];
        }
        //dd($permisos_rol);
        include_once '../view/roles/update.php';
    }

    public function postUpdate(){
        $obj = new MasterModel();

        $id_rol = $_POST['id_rol'];
        $rol_nombre = $_POST['rol_nombre'];

        $sql = "UPDATE roles SET nombre_rol = '$rol_nombre' WHERE id_rol = $id_rol";
        $obj->update($sql);

        $permisos = $_POST['permisos'];

        // Eliminar permisos anteriores
        $sql = "DELETE FROM permisos WHERE id_rol = $id_rol";
        $obj->delete($sql);

        // Insertar nuevos permisos
        foreach ($permisos as $modulo_id => $acciones) {
            foreach ($acciones as $accion_id => $valor) {

                $per_id = $obj->autoincrement("permisos", "id_permiso");
                $sql = "INSERT INTO permisos VALUES($per_id, $id_rol, $modulo_id, $accion_id)";
                
                $obj->insert($sql);
            }
        }

        redirect(getUrl("roles", "roles", "getRoles"));
    }
}

?>