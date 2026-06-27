<?php
require_once  dirname(__FILE__) . '/../MasterModel.php';
 
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
                    ST_AsText(s.coordenadas) AS coordenadas,
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
                    ST_AsText(s.coordenadas) AS coordenadas,
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
                    ST_AsText(s.coordenadas) AS coordenadas,
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

    // Funciones para el historial de reportes

    public function registrarHistorial($id_usuario, $tipo_reporte, $fecha_inicio, $fecha_fin, $id_estado_solicitud, $nombre_archivo) {
        $id_usuario   = (int) $id_usuario;
        $tipo_reporte = pg_escape_string($tipo_reporte);
        $fecha_inicio = pg_escape_string($fecha_inicio);
        $fecha_fin    = pg_escape_string($fecha_fin);
        $nombre_archivo = pg_escape_string($nombre_archivo);

        $estado_val = (!empty($id_estado_solicitud) && is_numeric($id_estado_solicitud)) ? (int)$id_estado_solicitud : 'NULL';

        $sql = "INSERT INTO historial_reportes
                    (id_usuario, tipo_reporte, fecha_inicio, fecha_fin, id_estado_solicitud, nombre_archivo)
                VALUES
                    ($id_usuario, '$tipo_reporte', '$fecha_inicio', '$fecha_fin', $estado_val, '$nombre_archivo')";

        return $this->select($sql);
    }

    // Función para obtener el historial de reportes generados por un usuario específico
    public function obtenerHistorial($id_usuario, $id_rol) {
            $id_usuario = (int) $id_usuario;
            $id_rol  = (int) $id_rol;
            $condicion = ($id_rol === 1) ? '' : "WHERE hr.id_usuario = $id_usuario";

            $sql = "SELECT hr.*, u.primer_nombre, u.primer_apellido
                    FROM historial_reportes hr
                    JOIN usuarios u ON u.id_usuario = hr.id_usuario
                    $condicion
                    ORDER BY hr.fecha_generacion DESC
                    LIMIT 50";

            return $this->select($sql);
        }
        // Función para obtener el total de solicitudes por tipo
        public function obtenerTotalesPorTipo() {
        $sql = "SELECT ts.nombre AS tipo, COUNT(s.id_solicitud) AS total
                FROM solicitudes s
                INNER JOIN tipos_solicitud ts ON s.id_tipo_solicitud = ts.id_tipo_solicitud
                GROUP BY ts.nombre
                ORDER BY total DESC";
        return $this->select($sql);
    }


}
?>
