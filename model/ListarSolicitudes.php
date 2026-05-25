<?php

class ListarSolicitudes {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // 🔹 Listar todas las solicitudes con joins útiles
    public function obtenerSolicitudes() {
        try {
            $sql = "
                SELECT 
                    s.id_solicitud,
                    s.fecha_creacion,
                    s.descripcion,
                    es.nombre_estado,
                    u.nombre AS nombre_usuario
                FROM solicitudes s
                INNER JOIN estados_solicitud es 
                    ON s.id_estado_solicitud = es.id_estado_solicitud
                INNER JOIN usuarios u 
                    ON s.id_usuario = u.id_usuario
                ORDER BY s.fecha_creacion DESC
            ";

            $stmt = $this->conexion->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [
                "error" => $e->getMessage()
            ];
        }
    }

}

?>