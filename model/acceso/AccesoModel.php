<?php
include_once '../model/MasterModel.php';

class AccesoModel extends MasterModel{
    // Obtiene los permisos asociados a un rol específico
    public function obtenerPermisosRol($id_rol)
    {
        $sql = "
            SELECT
                m.nombre_modulo,
                a.nombre_accion
            FROM permisos p
            INNER JOIN modulos m
                ON p.id_modulo = m.id_modulo
            INNER JOIN acciones a
                ON p.id_accion = a.id_accion
            WHERE p.id_rol = $id_rol";

        return $this->select($sql);
    }
    

}
?>