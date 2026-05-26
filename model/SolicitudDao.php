<?php

require_once __DIR__ . '/MasterModel.php';
require_once __DIR__ . '/Solicitud.php';


class SolicitudDao extends MasterModel {


    public function listarSolicitudes() {
        try {
            $sql = "
                SELECT 
                    s.id_solicitud,
                    s.descripcion,
                    s.fecha_solicitud,
                    s.id_estado_solicitud,
                    s.id_usuario,
                    s.tipo_solicitud,
                    s.direccion
                FROM solicitudes s
                ORDER BY s.id_solicitud DESC
            ";

            $result = $this->select($sql);

            // Validar que el resultado sea válido
            if (!$result) {
                error_log("Error en query de solicitudes: " . pg_last_error($this->getConnect()));
                return [];
            }

            $lista = [];

            while($row = pg_fetch_assoc($result)) {
                $solicitud = new Solicitud();

                $solicitud->setIdSolicitud($row['id_solicitud']);
                $solicitud->setDescripcion($row['descripcion']);
                $solicitud->setFechaCreacion($row['fecha_solicitud']);
                $solicitud->setIdEstadoSolicitud($row['id_estado_solicitud']);
                $solicitud->setIdUsuario($row['id_usuario']);
                $solicitud->setTipoSolicitud($row['tipo_solicitud']);
                $solicitud->setDireccion($row['direccion']);
                $lista[] = $solicitud;
            }

            error_log("Solicitudes obtenidas: " . count($lista));
            return $lista;
        } catch (Exception $e) {
            error_log("Excepción en listarSolicitudes: " . $e->getMessage());
            return [];
        }
    }
}   



?>