<?php

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
    $this -> $descripcion =  $descripcion;
    }

    public function getDescripcion() {
        return $this -> $descripcion;
    }

    public function setFechaCreacion ($fecha_creacion){
    $this -> $fecha_creacion = $fecha_creacion;
    }

    public function getFechaCreacion(){
        return $this -> $fecha_creacion;
    }

    public function setIdEstadoSolicitud($id_estado_solicitud){
        $this -> $id_estado_solicitud = $id_estado_solicitud;
    }
    
    public function getIdEstadoSolicitud(){
        return $this -> $id_estado_solicitud;
    }

    public function setIdUsuario($id_usuario){
        $this -> $id_usuario = $id_usuario;
    }


    public function getIdUsuario(){
        return $this -> $id_usuario;
    }
    

}

?>