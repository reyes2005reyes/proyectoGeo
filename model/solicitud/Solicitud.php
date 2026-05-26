<?php

require_once __DIR__ . '/../MasterModel.php';

class Solicitud extends MasterModel {

    private $id_solicitud;
    private $descripcion;
    private $fecha_creacion;
    private $id_estado_solicitud;
    private $id_usuario;
    private $tipo_solicitud;
    private $direccion;

    public function setIdSolicitud($id) { $this->id_solicitud = $id; }
    public function getIdSolicitud() { return $this->id_solicitud; }

    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function getDescripcion() { return $this->descripcion; }

    public function setFechaCreacion($fecha_creacion) { $this->fecha_creacion = $fecha_creacion; }
    public function getFechaCreacion() { return $this->fecha_creacion; }

    public function setIdEstadoSolicitud($id_estado_solicitud) { $this->id_estado_solicitud = $id_estado_solicitud; }
    public function getIdEstadoSolicitud() { return $this->id_estado_solicitud; }

    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }
    public function getIdUsuario() { return $this->id_usuario; }

    public function setTipoSolicitud($tipo_solicitud) { $this->tipo_solicitud = $tipo_solicitud; }
    public function getTipoSolicitud() { return $this->tipo_solicitud; }

    public function setDireccion($direccion) { $this->direccion = $direccion; }
    public function getDireccion() { return $this->direccion; }


    
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
            if (!$result) {
                error_log('Error en query de solicitudes: ' . pg_last_error($this->getConnect()));
                return [];
            }

            $lista = [];
            while ($row = pg_fetch_assoc($result)) {
                $sol = new Solicitud();
                $sol->setIdSolicitud($row['id_solicitud']);
                $sol->setDescripcion($row['descripcion']);
                $sol->setFechaCreacion($row['fecha_solicitud']);
                $sol->setIdEstadoSolicitud($row['id_estado_solicitud']);
                $sol->setIdUsuario($row['id_usuario']);
                $sol->setTipoSolicitud($row['tipo_solicitud']);
                $sol->setDireccion($row['direccion']);
                $lista[] = $sol;
            }

            error_log('Solicitudes obtenidas: ' . count($lista));
            return $lista;
        } catch (Exception $e) {
            error_log('Excepción en listarSolicitudes: ' . $e->getMessage());
            return [];
        }
    }
}
?>