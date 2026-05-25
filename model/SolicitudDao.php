<?php

require_once('MasterModel.php');
require_once("Solicitud.php");


class SolicitudDao extends MasterModel {


    public function listarSolicitudes() {
    $sql = "
            SELECT 
                s.id_solicitud,
                s.descripcion,
                s.fecha_creacion,
                s.id_estado_solicitud,
                s.id_usuario
            FROM solicitudes s
            ORDER BY s.id_solicitud DESC
        ";


        $result = $this -> select ($sql);

        $lista = [];

        while($row = pg_fetch_assoc($result)){
            $solicitud = new Solicitud();

            $solicitud -> setIdSolicitud($row['id_solicitud']);
            $solicitud -> setDescripcion($row['descripcion']);
            $solicitud -> setFechaCreacion($row['fecha_creacion']);
            $solicitud -> setIdEstadoSolicitud($row['id_estado_solicitud']);
            $solicitud -> setIdUsuario($row['id_usuario']);
            $lista[] = $solicitud;
        }

        return $lista;
    }
}   



?>