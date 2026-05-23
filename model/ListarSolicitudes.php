<?php

    class listarSolicitudes {

        public function listarSolicitudes() {
            $sql = "SELECT * FROM solicitudes";
            $stmt = Conexion::conectar()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    
?>