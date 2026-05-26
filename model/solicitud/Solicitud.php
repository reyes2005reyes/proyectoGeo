<?php


require_once __DIR__ . '/MasterModel.php';
require_once __DIR__ . '/Solicitud.php';



class Solicitud {

    private $id_solicitud;
    private $descripcion;
    private $fecha_creacion;
    private $id_estado_solicitud;
    private $id_usuario;

    // 🔹 Setters
    public function setIdSolicitud($id) {
        $this->id_solicitud = $id;
    }

    // 🔹 Getters
    public function getIdSolicitud() {
        return $this->id_solicitud;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setFechaCreacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }

    public function setIdEstadoSolicitud($id_estado_solicitud){
        $this->id_estado_solicitud = $id_estado_solicitud;
    }
    
    public function getIdEstadoSolicitud(){
        return $this->id_estado_solicitud;
    }

    public function setIdUsuario($id_usuario){
        $this->id_usuario = $id_usuario;
    }

    public function getIdUsuario(){
        return $this->id_usuario;
    }

    // Nuevos campos para tabla solicitudes
    private $tipo_solicitud;
    private $direccion;

    public function setTipoSolicitud($tipo_solicitud){
        $this->tipo_solicitud = $tipo_solicitud;
    }

    public function getTipoSolicitud(){
        return $this->tipo_solicitud;
    }

    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }

    public function getDireccion(){
        return $this->direccion;
    }
    

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