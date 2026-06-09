<?php

require_once __DIR__ . '/../MasterModel.php';

class Solicitud extends MasterModel {

    private $id_solicitud;
    private $descripcion;
    private $fecha_solicitud;
    private $id_estado_solicitud;
    private $id_usuario;
    private $id_tipo_solicitud;
    private $direccion;
    private $latitud;
    private $longitud;
    private $imagen;

    
    private $nombre_usuario;
    private $nombre_estado;
    private $nombre_tipo_solicitud;

    private $tiene_respuesta = false;

    private $detalle_especifico = null;

    // ======================
    // GETTERS / SETTERS
    // ======================

    public function setIdSolicitud($id) { $this->id_solicitud = $id; }
    public function getIdSolicitud() { return $this->id_solicitud; }

    public function setDescripcion($v) { $this->descripcion = $v; }
    public function getDescripcion() { return $this->descripcion; }

    public function setFechaSolicitud($v) { $this->fecha_solicitud = $v; }
    public function getFechaSolicitud() { return $this->fecha_solicitud; }

    public function setIdEstadoSolicitud($v) { $this->id_estado_solicitud = $v; }
    public function getIdEstadoSolicitud() { return $this->id_estado_solicitud; }

    public function setIdUsuario($v) { $this->id_usuario = $v; }
    public function getIdUsuario() { return $this->id_usuario; }

    public function setIdTipoSolicitud($v) { $this->id_tipo_solicitud = $v; }
    public function getIdTipoSolicitud() { return $this->id_tipo_solicitud; }

    public function setDireccion($v) { $this->direccion = $v; }
    public function getDireccion() { return $this->direccion; }

    public function setLatitud($v) { $this->latitud = $v; }
    public function getLatitud() { return $this->latitud; }

    public function setLongitud($v) { $this->longitud = $v; }
    public function getLongitud() { return $this->longitud; }

    public function setImagen($v) { $this->imagen = $v; }
    public function getImagen() { return $this->imagen; }

    public function setNombreUsuario($v) { $this->nombre_usuario = $v; }
    public function getNombreUsuario() { return $this->nombre_usuario; }

    public function setNombreEstado($v) { $this->nombre_estado = $v; }
    public function getNombreEstado() { return $this->nombre_estado; }

    public function setNombreTipoSolicitud($v) { $this->nombre_tipo_solicitud = $v; }
    public function getNombreTipoSolicitud() { return $this->nombre_tipo_solicitud; }

    public function setDetalleEspecifico($v) { $this->detalle_especifico = $v; }
    public function getDetalleEspecifico() { return $this->detalle_especifico; }

    //public function setIdUsuarioResponde($v){t}

    public function setTieneRespuesta($v) {
    $this->tiene_respuesta = $v;
    }

    public function getTieneRespuesta() {
        return $this->tiene_respuesta;
    }
    


    // ======================
    // BASE QUERY
    // ======================

    private function getBaseQuery() {
        return "
        SELECT
            s.id_solicitud,
            s.descripcion,
            s.fecha_solicitud,
            s.id_estado_solicitud,
            s.id_usuario,
            s.id_tipo_solicitud,
            s.direccion,
            s.latitud,
            s.longitud,
            s.imagen_url,

            es.nombre_estado_solicitud,
            ts.nombre AS nombre_tipo_solicitud,

            u.primer_nombre,
            u.primer_apellido

        FROM solicitudes s

        INNER JOIN usuarios u
            ON s.id_usuario = u.id_usuario

        INNER JOIN estados_solicitud es
            ON s.id_estado_solicitud = es.id_estado_solicitud

        INNER JOIN tipos_solicitud ts
            ON s.id_tipo_solicitud = ts.id_tipo_solicitud
    ";
}

    // ======================
    // MAPEO
    // ======================

    private function mapRowToSolicitud($row) {

        $sol = new Solicitud();

        $sol->setIdSolicitud($row['id_solicitud']);
        $sol->setDescripcion($row['descripcion']);
        $sol->setFechaSolicitud($row['fecha_solicitud']);
        $sol->setIdEstadoSolicitud($row['id_estado_solicitud']);
        $sol->setIdUsuario($row['id_usuario']);
        $sol->setIdTipoSolicitud($row['id_tipo_solicitud']);
        $sol->setDireccion($row['direccion']);
        $sol->setLatitud($row['latitud']);
        $sol->setLongitud($row['longitud']);
        $sol->setImagen($row['imagen_url']);

        $sol->setNombreEstado($row['nombre_estado_solicitud']);
        $sol->setNombreTipoSolicitud($row['nombre_tipo_solicitud']);

        //$sol->setIdUsuarioResponde($row['id_usuario_responde']);
        //$sol->setFechaRespuesta($row['fecha_respuesta']);

        $sol->setNombreUsuario(
            ($row['primer_nombre'] ?? '') . ' ' . ($row['primer_apellido'] ?? '')
        );

        $sol->setDetalleEspecifico(
            $this->obtenerDetallePorTipo(
                $row['id_solicitud'],
                $row['id_tipo_solicitud']
            )
        );

        return $sol;
    }


public function listarSolicitudes($idUsuario = null) {

    try {

        // 🔥 Base query
        $sql = $this->getBaseQuery();

        // 🔥 Si viene usuario → filtrar
        if ($idUsuario !== null) {

            $sql .= " WHERE s.id_usuario = $1
                      ORDER BY s.id_solicitud DESC";

            $result = $this->query($sql, [$idUsuario]);

        } else {

            // 🔥 Sin filtro → todos
            $sql .= " ORDER BY s.id_solicitud DESC";

            $result = $this->query($sql);
        }

        $lista = [];

        while ($row = pg_fetch_assoc($result)) {
            $lista[] = $this->mapRowToSolicitud($row);
        }

        return $lista;

    } catch (Exception $e) {

        error_log("Error listarSolicitudes: " . $e->getMessage());
        return [];
    }
}

    // ======================
    // POR ID
    // ======================

    public function obtenerSolicitudPorId($id) {

        try {

            $sql = $this->getBaseQuery() .
                " WHERE s.id_solicitud = $1";

            $result = $this->query($sql, [$id]);

            if ($row = pg_fetch_assoc($result)) {
                return $this->mapRowToSolicitud($row);
            }

            return null;

        } catch (Exception $e) {

            error_log(
                "Error obtenerSolicitudPorId: " .
                $e->getMessage()
            );

            return null;
        }
    }

    // ======================
    // QUERY HELP
    // ======================

    private function queryOne($sql, $params = []) {
        $res = $this->query($sql, $params);
        return pg_fetch_assoc($res) ?: null;
    }

    // ======================
    // DETALLES POR TIPO
    // ======================

    private function obtenerDetallePorTipo($idSolicitud, $idTipo) {

        switch ($idTipo) {

            case 1:
                return $this->queryOne("
                    SELECT * FROM solicitudes_reporte_accidentes
                    WHERE id_solicitud = $1
                ", [$idSolicitud]);

            case 2:
                return $this->queryOne("
                    SELECT sm.*, c.nombre_categoria, ts.nombre_tipo_senal
                    FROM solicitudes_senal_mal_estado sm
                    INNER JOIN categorias c ON sm.id_categoria = c.id_categoria
                    INNER JOIN tipos_senal ts ON sm.id_tipo_senal = ts.id_tipo_senal
                    WHERE sm.id_solicitud = $1
                ", [$idSolicitud]);

            case 3:
                return $this->queryOne("
                    SELECT * FROM solicitudes_nueva_senalizacion
                    WHERE id_solicitud = $1
                ", [$idSolicitud]);

            case 4:
                return $this->queryOne("
                    SELECT * FROM solicitudes_reductor_mal_estado
                    WHERE id_solicitud = $1
                ", [$idSolicitud]);

            case 5:
                return $this->queryOne("
                    SELECT * FROM solicitudes_nuevo_reductor
                    WHERE id_solicitud = $1
                ", [$idSolicitud]);

            case 6:
                return $this->queryOne("
                    SELECT * FROM solicitudes_via_publica_mal_estado
                    WHERE id_solicitud = $1
                ", [$idSolicitud]);

            case 7:
                return $this->queryOne("
                    SELECT p.*, t.tipo_pqrsf
                    FROM solicitudes_pqrsf p
                    INNER JOIN tipos_pqrsf t ON p.id_tipo_pqrsf = t.id_tipo_pqrsf
                    WHERE p.id_solicitud = $1
                ", [$idSolicitud]);

            default:
                return null;
        }
    }

    // ======================
    // COLOR ESTADO
    // ======================

    public function getColorEstado() {

        $estado = strtolower($this->nombre_estado);

        return match($estado) {
            'pendiente' => 'warning',
            'en revisión' => 'info',
            'en proceso' => 'primary',
            'rechazada' => 'danger',
            'completada' => 'success',
            default => 'secondary'
        };
    }

public function registrarRespuesta(
    $idSolicitud,
    $idFuncionario,
    $mensaje,
    $idEstado
    ) {

    try {

        if ($this->yaTieneRespuesta($idSolicitud)) {

            return [
                'ok' => false,
                'msg' => 'La solicitud ya fue respondida'
            ];
        }

        $sql = "
            INSERT INTO respuestas_solicitud (
                id_solicitud,
                id_usuario_respuesta,
                id_estado_solicitud,
                mensaje,
                fecha
            )
            VALUES ($1,$2,$3,$4,NOW())
        ";

        $this->query(
            $sql,
            [
                $idSolicitud,
                $idFuncionario,
                $idEstado,
                $mensaje
            ]
        );

        $sqlUpdate = "
            UPDATE solicitudes
            SET id_estado_solicitud = $1
            WHERE id_solicitud = $2
        ";

        $this->query(
            $sqlUpdate,
            [
                $idEstado,
                $idSolicitud
            ]
        );

        return [
            'ok' => true,
            'msg' => 'Respuesta registrada correctamente'
        ];

    } catch (Exception $e) {

        error_log(
            'Error registrarRespuesta: ' .
            $e->getMessage()
        );

        return [
            'ok' => false,
            'msg' => 'Error interno'
        ];
    }
}


public function obtenerRespuesta($idSolicitud){
    $sql = "
        SELECT
            r.*,
            u.primer_nombre,
            u.primer_apellido,
            e.nombre_estado_solicitud

        FROM respuestas_solicitud r

        INNER JOIN usuarios u
            ON r.id_usuario_respuesta = u.id_usuario

        INNER JOIN estados_solicitud e
            ON r.id_estado_solicitud = e.id_estado_solicitud

        WHERE r.id_solicitud = $1

        LIMIT 1
    ";

    $result = $this->query($sql, [$idSolicitud]);

    return pg_fetch_assoc($result);
}

public function yaTieneRespuesta($idSolicitud){
    $sql = "
        SELECT id_respuesta
        FROM respuestas_solicitud
        WHERE id_solicitud = $1
        LIMIT 1
    ";

    $result = $this->query($sql, [$idSolicitud]);

    return pg_num_rows($result) > 0;
}

public function verificarPermiso($idRol, $idModulo, $idAccion) {

    $sql = "
        SELECT 1
        FROM permisos
        WHERE id_rol = $1
        AND id_modulo = $2
        AND id_accion = $3
        LIMIT 1
    ";

    $result = $this->query($sql, [$idRol, $idModulo, $idAccion]);

    return pg_num_rows($result) > 0;
}



public function actualizarEstadoSolicitud($idSolicitud, $idEstado)
{
    try {

        // 🚫 VALIDACIÓN DE NEGOCIO: si ya tiene respuesta, no se puede cambiar estado
        if ($this->yaTieneRespuesta($idSolicitud)) {

            return [
                'ok' => false,
                'msg' => 'No se puede actualizar el estado porque la solicitud ya tiene una respuesta registrada'
            ];
        }

        $sql = "
            UPDATE solicitudes
            SET id_estado_solicitud = $1
            WHERE id_solicitud = $2
        ";

        $this->query($sql, [
            $idEstado,
            $idSolicitud
        ]);

        return [
            'ok' => true,
            'msg' => 'Estado actualizado correctamente'
        ];

    } catch (Exception $e) {

        error_log("Error actualizarEstadoSolicitud: " . $e->getMessage());

        return [
            'ok' => false,
            'msg' => 'Error al actualizar estado'
        ];
    }
}
}