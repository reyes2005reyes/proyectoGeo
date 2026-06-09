<?php
require_once __DIR__ . '/../MasterModel.php';
 
class ReportesModel extends MasterModel {
    // 1 Accidentes de tránsito
    public function obtenerAccidentes($fecha_inicio, $fecha_fin, $estado) {
        $fecha_inicio = pg_escape_string($fecha_inicio);
        $fecha_fin = pg_escape_string($fecha_fin);

        $condicion_estado = '';
        if (!empty($estado) && is_numeric($estado)) {
            $condicion_estado = "AND s.id_estado_solicitud = $estado";
        }

        $sql = "SELECT
                    s.id_solicitud AS radicado,
                    ts.nombre AS tipo_solicitud,
                    TO_CHAR(s.fecha_solicitud, 'DD/MM/YYYY') AS fecha_registro,
                    s.direccion  AS ubicacion,
                    CONCAT(s.latitud, ', ', s.longitud) AS coordenadas,
                    s.descripcion AS descripcion,
                    es.nombre_estado_solicitud AS estado
                FROM solicitudes s
                INNER JOIN tipos_solicitud ts ON s.id_tipo_solicitud = ts.id_tipo_solicitud
                INNER JOIN estados_solicitud es ON s.id_estado_solicitud = es.id_estado_solicitud
                WHERE s.id_tipo_solicitud = 1
                    AND s.fecha_solicitud::DATE BETWEEN '$fecha_inicio' AND '$fecha_fin'
                    $condicion_estado
                ORDER BY s.fecha_solicitud DESC";

        return $this->select($sql);
    }


    // 2 Solicitudes de señalizacion vial en mal estado

    public function obtenerSenalesmalEstado($fecha_inicio, $fecha_fin, $estado) {
        $fecha_inicio = pg_escape_string($fecha_inicio);
        $fecha_fin = pg_escape_string($fecha_fin);

        $condicion_estado = '';
        if (!empty($estado) && is_numeric($estado)) {
            $condicion_estado = "AND s.id_estado_solicitud = $estado";
        }

        $sql = "SELECT
                    s.id_solicitud AS radicado,
                    ts.nombre AS tipo_solicitud,
                    TO_CHAR(s.fecha_solicitud, 'DD/MM/YYYY') AS fecha_registro,
                    s.direccion AS ubicacion,
                    CONCAT(s.latitud, ', ', s.longitud) AS coordenadas,
                    s.descripcion AS descripcion,
                    es.nombre_estado_solicitud AS estado
                FROM solicitudes s
                INNER JOIN tipos_solicitud ts ON s.id_tipo_solicitud   = ts.id_tipo_solicitud
                INNER JOIN estados_solicitud es ON s.id_estado_solicitud = es.id_estado_solicitud
                WHERE s.id_tipo_solicitud = 2
                    AND s.fecha_solicitud::DATE BETWEEN '$fecha_inicio' AND '$fecha_fin' $condicion_estado
                ORDER BY s.fecha_solicitud DESC";

        return $this->select($sql);
    }


    // 3  Solicitudes de reductores de velocidad en mal estado
    public function obtenerReductoresMalEstado($fecha_inicio, $fecha_fin, $estado) {
        $fecha_inicio = pg_escape_string($fecha_inicio);
        $fecha_fin    = pg_escape_string($fecha_fin);

        $condicion_estado = '';
        if (!empty($estado) && is_numeric($estado)) {
            $condicion_estado = "AND s.id_estado_solicitud = $estado";
        }

        $sql = "SELECT
                    s.id_solicitud AS radicado,
                    ts.nombre AS tipo_solicitud,
                    TO_CHAR(s.fecha_solicitud, 'DD/MM/YYYY') AS fecha_registro,
                    s.direccion AS ubicacion,
                    CONCAT(s.latitud, ', ', s.longitud) AS coordenadas,
                    s.descripcion AS descripcion,
                    es.nombre_estado_solicitud AS estado
                FROM solicitudes s
                INNER JOIN tipos_solicitud ts ON s.id_tipo_solicitud   = ts.id_tipo_solicitud
                INNER JOIN estados_solicitud es ON s.id_estado_solicitud = es.id_estado_solicitud
                WHERE s.id_tipo_solicitud = 4
                    AND s.fecha_solicitud::DATE BETWEEN '$fecha_inicio' AND '$fecha_fin' $condicion_estado
                ORDER BY s.fecha_solicitud DESC";

        return $this->select($sql);
    }

    // para el filtro select de la vista
    public function obtenerEstados() {
        $sql = "SELECT id_estado_solicitud, nombre_estado_solicitud
                FROM estados_solicitud
                ORDER BY id_estado_solicitud";
        return $this->select($sql);
    }
}
?>
