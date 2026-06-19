<?php

    require_once dirname(__FILE__) . '/../MasterModel.php';

    class Solicitud extends MasterModel {

    //DATOS TABLA SOLICITUD
        private $id_solicitud;
        private $id_usuario;
        private $id_estado_solicitud;
        private $id_tipo_solicitud;
        private $descripcion;
        private $direccion;
        private $latitud;
        private $longitud;
        private $imagen;
        private $fecha_solicitud;
    //--------------------------------------------------
    //DATOS de la tabla de usuarios
        private $nombre_usuario;  //Aqui pongo nombre  y apellido
    //--------------------------------------------------
    //DATOS de la tabla de estados_solicitud
        private $nombre_estado; // nombre_estado_solicitud 
    //-------------------------------------------------------
        private $tiene_respuesta = false; 

        private $detalle_especifico = null; 

        private $tipo_choque;
        private $tipo_senal;
        private $tipo_danio;
        private $tipo_reductor;
        private $tipo_pqrsf;
        private $vehiculos;
        private $lesionados;

    //-----------------------------------------------------------------
        private $id_nueva_senalizacion;  //de solicitudes_nueva_senalizacion
        private $id_nuevo_reductor; //solicitudes_nuevo_reductor
        private $id_pqrsf; // de solicitudes_pqrsf
        private $id_tipo_pqrsf; // de  tipos_pqrsf

        private $id_reductor_mal_estado; //de solicitudes_reductor_mal_estado
        private $solicitudes_reporte_accidentes; //de solicitudes_reporte_accidentes
        private $id_senal_mal_estado; //de solicitudes_senal_mal_estado
        private $id_via_publica_mal_estado; // de solicitudes_via_publica_mal_estado
    //-------------------------------------------------------------------------
    //DATOS de la tabla de nombre_categoria
        private $id_categoria;
        private $nombre_categoria;
        private $descripcion_categoria;
    //------------------------------------------------------------------------
    //DATOS de la tabla tipos_danio
        private $id_tipo_danio;
        private $nombre_tipo_danio;
        private $descripcion_danio;
    //----------------------------------------------------------------------
    //DATOS de la tabla tipos_reductor
        private $id_tipo_reductor;
        private $nombre_tipo_reductor;
        private $descripcion_reductor; //descripcion
    //---------------------------------------------------------------------
    //DATOS  de la tabla orientaciones
        private $id_orientacion;
        private $nombre_orientacion;
    //---------------------------------------------------------------------
    //DATOS de la tipos_senal
        private $id_tipo_senal;
        private $nombre_tipo_senal;
    //--------------------------------------------------------------
        //DATOS de la tabla de tipos_solicitud;
        private $nombre_tipo_solicitud; // codigo
    //----------------------------------------------------------
    //DATOS de la tabla causas_accidente;
        private $id_causa_accidente;
        private $nombre_causa;
    //-------------------------------------------------
    //DATOS de la tabla tipos_choque;
        private $id_tipo_choque;
        private $nombre_tipo_choque;
    //-------------------------------------------------
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

        public function setTieneRespuesta($v) {$this->tiene_respuesta = $v;}
        public function getTieneRespuesta() {return $this->tiene_respuesta;}

        public function setNombre_categoria($nombre_categoria){$this -> nombre_categoria = $nombre_categoria;}
        public function getNombre_categoria(){return $this -> nombre_categoria;}
        public function setId_categoria($id_categoria){$this -> id_categoria = $id_categoria;}
        public function getId_categoria(){return $this -> id_categoria;}

        public function setNombre_tipo_danio($nombre_tipo_danio){$this -> nombre_tipo_danio = $nombre_tipo_danio;}
        public function getNombre_tipo_danio(){return $this -> nombre_tipo_danio;}
        public function setId_tipo_danio($id_tipo_danio){$this -> id_tipo_danio = $id_tipo_danio;}
        public function getId_tipo_danio(){return $this-> id_tipo_danio;}
        public function setDescripcion_danio($descripcion_danio){$this -> descripcion_danio = $descripcion_danio;}
        public function getDescripcion_danio(){return $this -> descripcion_danio;}

        public function setNombre_tipo_reductor($nombre_tipo_reductor){$this -> nombre_tipo_reductor = $nombre_tipo_reductor;}
        public function getNombre_tipo_reductor(){return $this -> nombre_tipo_reductor;}
        public function setDescripcion_reductor($descripcion_reductor){$this -> descripcion_reductor = $descripcion_reductor;}
        public function getDescripcion_reductor(){return $this -> descripcion_reductor;}
        public function setId_tipo_reductor($id_tipo_reductor){$this -> id_tipo_reductor = $id_tipo_reductor;}
        public function getId_tipo_reductor(){return $this -> id_tipo_reductor;}

        public function setId_orientacion($id_orientacion){$this -> id_orientacion = $id_orientacion;}
        public function getId_orientacion(){return $this -> id_orientacion;}
        public function setNombre_orientacion($nombre_orientacion){$this -> nombre_orientacion = $nombre_orientacion;}
        public function getNombre_orientacion(){return $this -> nombre_orientacion;}

        public function setId_tipo_senal($id_tipo_senal){$this -> id_tipo_senal = $id_tipo_senal;}
        public function getId_tipo_senal(){return $this -> id_tipo_senal;}
        public function setNombre_tipo_senal($nombre_tipo_senal){$this -> nombre_tipo_senal = $nombre_tipo_senal;}
        public function getNombre_tipo_senal(){return $this -> nombre_tipo_senal;}

        public function setNombre_tipo_solicitud($nombre_tipo_solicitud){$this -> nombre_tipo_solicitud = $nombre_tipo_solicitud;}
        public function getNombre_tipo_solicitud(){return $this -> nombre_tipo_solicitud;}

        public function setId_causa_accidente($id_causa_accidente){$this -> id_causa_accidente = $id_causa_accidente;}
        public function getId_causa_accidente(){return $this -> id_causa_accidente;}

        public function setNombre_causa($nombre_causa){$this -> nombre_causa = $nombre_causa;}
        public function getNombre_causa(){return $this -> nombre_causa;}

        public function setId_tipo_choque($id_tipo_choque){$this -> id_tipo_choque = $id_tipo_choque;}
        public function getId_tipo_choque(){return $this -> id_tipo_choque;}

        public function setNombre_tipo_choque($nombre_tipo_choque){$this -> nombre_tipo_choque = $nombre_tipo_choque;}
        public function getNombre_tipo_choque(){return $this -> nombre_tipo_choque;}
        

        //DATOS de solicitudes_nueva_senalizacion
        public function setId_nueva_senalizacion($id_nueva_senalizacion){$this -> id_nueva_senalizacion = $id_nueva_senalizacion;}
        public function getId_nueva_senalizacion(){return $this -> id_nueva_senalizacion;}
        //DATOS solicitudes_nuevo_reductor
        public function setId_nuevo_reductor($id_nuevo_reductor){$this -> id_nuevo_reductor = $id_nuevo_reductor;}
        public function getId_nuevo_reductor(){return $this -> id_nuevo_reductor;}
        //DATOS solicitudes_pqrsf
        public function setId_pqrsf($id_pqrsf){ $this -> id_pqrsf = $id_pqrsf;}
        public function getId_pqrsf(){return $this -> id_pqrsf;}
        //tipos_pqrsf
        public function setId_tipo_pqrsf($id_tipo_pqrsf){$this -> id_tipo_pqrsf = $id_tipo_pqrsf;}
        public function getId_tipo_pqrsf(){return $this -> id_tipo_pqrsf;}

        public function setTipoChoque($v){ $this->tipo_choque = $v; }
        public function getTipoChoque(){ return $this->tipo_choque; }

        public function setTipoSenal($v){ $this->tipo_senal = $v; }
        public function getTipoSenal(){ return $this->tipo_senal; }

        public function setTipoDanio($v){ $this->tipo_danio = $v; }
        public function getTipoDanio(){ return $this->tipo_danio; }

        public function setTipoReductor($v){ $this->tipo_reductor = $v; }
        public function getTipoReductor(){ return $this->tipo_reductor; }

        public function setTipoPQRSF($v){ $this->tipo_pqrsf = $v; }
        public function getTipoPQRSF(){ return $this->tipo_pqrsf; }

        public function setVehiculos($v){ $this->vehiculos = $v; }
        public function getVehiculos(){ return $this->vehiculos; }

        public function setLesionados($v){ $this->lesionados = $v; }
        public function getLesionados(){ return $this->lesionados; }

        public function beginTransaction() {
                pg_query($this->getConnect(), "BEGIN");
        }

        public function commit() {
                pg_query($this->getConnect(), "COMMIT");
        }

        public function rollback() {
                pg_query($this->getConnect(), "ROLLBACK");
        }
        

        //APLICANDO ALGO DE POLIMORFISMO JEJE.
        // Aplicando polimorfismo.
        public function insert($sql, $params = array()) {

            // Si no hay parámetros, conserva el comportamiento original.
            if (empty($params)) {
                return parent::insert($sql);
            }

            // Si hay parámetros, utiliza consultas parametrizadas.
            return $this->query($sql, $params);
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

                ST_Y(s.coordenadas) AS latitud,
                ST_X(s.coordenadas) AS longitud,

                s.imagen_url,

                es.nombre_estado_solicitud,
                ts.nombre AS nombre_tipo_solicitud,

                u.primer_nombre,
                u.primer_apellido,

                sra.id_solicitud_reporte_accidente,
                sra.observacion,

                ca.nombre_causa,
                tc.nombre_tipo_choque,

                ts1.nombre_tipo_senal AS tipo_senal_mal_estado,
                ts2.nombre_tipo_senal AS tipo_nueva_senalizacion,

                tp.tipo_pqrsf,

                td1.nombre_tipo_danio AS danio_senal,
                td2.nombre_tipo_danio AS danio_reductor,
                td3.nombre_tipo_danio AS danio_via_publica,

                tr1.nombre_tipo_reductor AS reductor_mal_estado,
                tr2.nombre_tipo_reductor AS nuevo_reductor,

                veh.vehiculos,
                les.lesionados

            FROM solicitudes s

            LEFT JOIN usuarios u
                ON u.id_usuario = s.id_usuario

            LEFT JOIN estados_solicitud es
                ON es.id_estado_solicitud = s.id_estado_solicitud

            INNER JOIN tipos_solicitud ts
                ON ts.id_tipo_solicitud = s.id_tipo_solicitud

            LEFT JOIN solicitudes_reporte_accidentes sra
                ON sra.id_solicitud = s.id_solicitud

            LEFT JOIN causas_accidente ca
                ON ca.id_causa_accidente = sra.id_causa_accidente

            LEFT JOIN tipos_choque tc
                ON tc.id_tipo_choque = ca.id_tipo_choque

            LEFT JOIN solicitudes_senal_mal_estado ssme
                ON ssme.id_solicitud = s.id_solicitud

            LEFT JOIN solicitudes_nueva_senalizacion sns
                ON sns.id_solicitud = s.id_solicitud

            LEFT JOIN solicitudes_reductor_mal_estado srme
                ON srme.id_solicitud = s.id_solicitud

            LEFT JOIN solicitudes_nuevo_reductor snr
                ON snr.id_solicitud = s.id_solicitud

            LEFT JOIN solicitudes_via_publica_mal_estado svpm
                ON svpm.id_solicitud = s.id_solicitud

            LEFT JOIN solicitudes_pqrsf sp
                ON sp.id_solicitud = s.id_solicitud

            LEFT JOIN tipos_senal ts1
                ON ts1.id_tipo_senal = ssme.id_tipo_senal

            LEFT JOIN tipos_senal ts2
                ON ts2.id_tipo_senal = sns.id_tipo_senal

            LEFT JOIN tipos_danio td1
                ON td1.id_tipo_danio = ssme.id_tipo_danio

            LEFT JOIN tipos_danio td2
                ON td2.id_tipo_danio = srme.id_tipo_danio

            LEFT JOIN tipos_danio td3
                ON td3.id_tipo_danio = svpm.id_tipo_danio

            LEFT JOIN tipos_reductor tr1
                ON tr1.id_tipo_reductor = srme.id_tipo_reductor

            LEFT JOIN tipos_reductor tr2
                ON tr2.id_tipo_reductor = snr.id_tipo_reductor

            LEFT JOIN tipos_pqrsf tp
                ON tp.id_tipo_pqrsf = sp.id_tipo_pqrsf

            LEFT JOIN (
                SELECT
                    v.id_solicitud_reporte_accidente,
                    STRING_AGG(tv.nombre_vehiculo, ', ') AS vehiculos
                FROM vehiculos v
                INNER JOIN tipos_vehiculo tv
                    ON tv.id_tipo_vehiculo = v.id_tipo_vehiculo
                GROUP BY v.id_solicitud_reporte_accidente
            ) veh
                ON veh.id_solicitud_reporte_accidente =
                sra.id_solicitud_reporte_accidente

            LEFT JOIN (
                SELECT
                    rl.id_solicitud_reporte_accidente,
                    STRING_AGG(l.nombre_completo, ', ') AS lesionados
                FROM reporte_lesionado rl
                INNER JOIN lesionados l
                    ON l.id_lesionado = rl.id_lesionado
                GROUP BY rl.id_solicitud_reporte_accidente
            ) les
                ON les.id_solicitud_reporte_accidente =
                sra.id_solicitud_reporte_accidente
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
                (isset($row['primer_nombre']) ? $row['primer_nombre'] : '') . ' ' . (isset($row['primer_apellido']) ? $row['primer_apellido'] : '')
            );

            // Map type-specific fields (if present in the query)
            $sol->setNombre_causa(isset($row['nombre_causa']) ? $row['nombre_causa'] : null);

            $sol->setTipoChoque(
            isset($row['nombre_tipo_choque']) ? $row['nombre_tipo_choque'] : null
            );

            $sol->setTipoSenal(
                isset($row['tipo_senal_mal_estado']) ? $row['tipo_senal_mal_estado']
                : (isset($row['tipo_nueva_senalizacion']) ? $row['tipo_nueva_senalizacion']
                : null)
            );

            $sol->setTipoDanio(
                isset($row['danio_senal']) ? $row['danio_senal']
                : (isset($row['danio_reductor']) ? $row['danio_reductor']
                : (isset($row['danio_via_publica']) ? $row['danio_via_publica']
                : null))
            );

            $sol->setTipoReductor(
                isset($row['reductor_mal_estado']) ? $row['reductor_mal_estado']
                : (isset($row['nuevo_reductor']) ? $row['nuevo_reductor']
                : null)
            );

            $sol->setTipoPQRSF(
                isset($row['tipo_pqrsf']) ? $row['tipo_pqrsf'] : null
            );

            $sol->setVehiculos(
                isset($row['vehiculos']) ? $row['vehiculos'] : null
            );

            $sol->setLesionados(
                isset($row['lesionados']) ? $row['lesionados'] : null
            );


            $sol->setNombre_tipo_choque(isset($row['nombre_tipo_choque']) ? $row['nombre_tipo_choque'] : null);

            $sol->setNombre_tipo_senal(isset($row['tipo_senal_mal_estado']) ? $row['tipo_senal_mal_estado'] : null);
            // nueva señalización
            $sol->setNombre_tipo_senal(isset($row['tipo_nueva_senalizacion']) ? $row['tipo_nueva_senalizacion'] : $sol->getNombre_tipo_senal());

            $sol->setNombre_tipo_danio(isset($row['danio_senal']) ? $row['danio_senal'] : (isset($row['danio_reductor']) ? $row['danio_reductor'] : (isset($row['danio_via_publica']) ? $row['danio_via_publica'] : null)));
            $sol->setNombre_tipo_reductor(isset($row['reductor_mal_estado']) ? $row['reductor_mal_estado'] : (isset($row['nuevo_reductor']) ? $row['nuevo_reductor'] : null));

            // PQRSF
            $pqrsf = isset($row['tipo_pqrsf']) ? $row['tipo_pqrsf'] : null;

            // Vehículos y lesionados (ya vienen como strings agregadas)
            $vehiculos = isset($row['vehiculos']) ? $row['vehiculos'] : null;
            $lesionados = isset($row['lesionados']) ? $row['lesionados'] : null;

            // Construir detalle específico legible
            $detalle = array();
            if (!empty($row['nombre_causa'])) {
                $detalle[] = 'Causa accidente: ' . $row['nombre_causa'];
            }
            if (!empty($row['nombre_tipo_choque'])) {
                $detalle[] = 'Tipo choque: ' . $row['nombre_tipo_choque'];
            }
            if (!empty($row['tipo_senal_mal_estado'])) {
                $detalle[] = 'Señal (mal estado): ' . $row['tipo_senal_mal_estado'];
            }
            if (!empty($row['tipo_nueva_senalizacion'])) {
                $detalle[] = 'Nueva señal: ' . $row['tipo_nueva_senalizacion'];
            }
            if (!empty($row['danio_senal'])) {
                $detalle[] = 'Daño señal: ' . $row['danio_senal'];
            }
            if (!empty($row['danio_reductor'])) {
                $detalle[] = 'Daño reductor: ' . $row['danio_reductor'];
            }
            if (!empty($row['danio_via_publica'])) {
                $detalle[] = 'Daño vía pública: ' . $row['danio_via_publica'];
            }
            if (!empty($row['reductor_mal_estado'])) {
                $detalle[] = 'Reductor: ' . $row['reductor_mal_estado'];
            }
            if (!empty($row['nuevo_reductor'])) {
                $detalle[] = 'Nuevo reductor: ' . $row['nuevo_reductor'];
            }
            if (!empty($pqrsf)) {
                $detalle[] = 'PQRSF: ' . $pqrsf;
            }
            if (!empty($vehiculos)) {
                $detalle[] = 'Vehículos: ' . $vehiculos;
            }
            if (!empty($lesionados)) {
                $detalle[] = 'Lesionados: ' . $lesionados;
            }

            $sol->setDetalleEspecifico(!empty($detalle) ? implode("\n", $detalle) : null);

            return $sol;
        }

        private function queryOne($sql, $params = array()) {

            $res = $this->query($sql, $params);
            return pg_fetch_assoc($res) ?: null;
        }

            $fila = pg_fetch_assoc($res);

            if ($fila) {
                return $fila;
            }

            return null;
        }


    public function listarSolicitudes($idUsuario = null) {

        try {

            // Base query
            $sql = $this->getBaseQuery();

            // Si viene usuario → filtrar
            if ($idUsuario !== null) {

                $sql .= " WHERE s.id_usuario = $1
                        ORDER BY s.id_solicitud DESC";

                $result = $this->query($sql, array($idUsuario));

            } else {

                // Sin filtro → todos
                $sql .= " ORDER BY s.id_solicitud DESC";

                $result = $this->query($sql);
            }

            $lista = array();

            while ($row = pg_fetch_assoc($result)) {
                $lista[] = $this->mapRowToSolicitud($row);
            }

            return $lista;

        } catch (Exception $e) {

            error_log("Error listarSolicitudes: " . $e->getMessage());
            return array();
        }
    }

        // ======================
        // POR ID
        // ======================

        public function obtenerSolicitudPorId($id) {



            try {

                $sql = $this->getBaseQuery() .
                    " WHERE s.id_solicitud = $1";

                $result = $this->query($sql, array($id));

                
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

        

        // ======================
        // DETALLES POR TIPO
        // ======================

        

        // ======================
        // COLOR ESTADO
        // ======================

        public function getColorEstado() {

            $estado = strtolower($this->nombre_estado);

            if ($estado == 'pendiente') {
                return 'warning';
            } elseif ($estado == 'en revisión') {
                return 'info';
            } elseif ($estado == 'en proceso') {
                return 'primary';
            } elseif ($estado == 'rechazada') {
                return 'danger';
            } elseif ($estado == 'completada') {
                return 'success';
            } else {
                return 'secondary';
            }
        }

    public function registrarRespuesta(
        $idSolicitud,
        $idFuncionario,
        $mensaje,
        $idEstado
        ) {

        try {

            if ($this->yaTieneRespuesta($idSolicitud)) {

                return array(
                    'ok' => false,
                    'msg' => 'La solicitud ya fue respondida'
                );
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

            return array(
                'ok' => true,
                'msg' => 'Respuesta registrada correctamente'
            );

        } catch (Exception $e) {

            error_log(
                'Error registrarRespuesta: ' .
                $e->getMessage()
            );

            return array(
                'ok' => false,
                'msg' => 'Error interno'
            );
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

        $result = $this->query($sql, array($idSolicitud));

        return pg_fetch_assoc($result);
    }

    public function yaTieneRespuesta($idSolicitud){
        $sql = "
            SELECT id_respuesta
            FROM respuestas_solicitud
            WHERE id_solicitud = $1
            LIMIT 1
        ";

        $result = $this->query($sql, array($idSolicitud));

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

        $result = $this->query($sql, array($idRol, $idModulo, $idAccion));

        return pg_num_rows($result) > 0;
    }



    public function actualizarEstadoSolicitud($idSolicitud, $idEstado) {

        try {

            // =========================
            // 1. OBTENER ESTADO ACTUAL
            // =========================
            $estadoActual = $this->queryOne("
                SELECT id_estado_solicitud 
                FROM solicitudes 
                WHERE id_solicitud = $1
            ", array($idSolicitud));

            if (!$estadoActual) {
                return array(
                    'ok' => false,
                    'msg' => 'Solicitud no encontrada'
                );
            }

            $estadoActualId = $estadoActual['id_estado_solicitud'];

            

            // =========================
            // 3. VALIDACIÓN: reglas de negocio
            // =========================
            if (!$this->esTransicionValida($estadoActualId, $idEstado)) {
                return array(
                    'ok' => false,
                    'msg' => 'Transición de estado no permitida'
                );
            }

            // =========================
            // 4. ACTUALIZAR ESTADO
            // =========================
            $sql = "
                UPDATE solicitudes
                SET id_estado_solicitud = $1
                WHERE id_solicitud = $2
            ";

            $this->query($sql, array(
                $idEstado,
                $idSolicitud
            ));

            return array(
                'ok' => true,
                'msg' => 'Estado actualizado correctamente'
            );

        } catch (Exception $e) {

        echo "<pre>";
        echo "ERROR DETECTADO:\n";
        echo $e->getMessage();
        echo "\n\nTRACE:\n";
        echo $e->getTraceAsString();
        echo "</pre>";

        exit;

            error_log("Error actualizarEstadoSolicitud: " . $e->getMessage());

            return array(
                'ok' => false,
                'msg' => 'Error al actualizar estado'
            );
        }
    }


    public function esTransicionValida($estadoActual, $estadoNuevo) {
        // No permitir cambios si ya está completada
        if ($estadoActual == 5) {
            return false;
        }

        // No permitir volver a pendiente
        if ($estadoNuevo == 1) {
            return false;
        }

        // ✔ Rechazo permitido desde cualquier estado excepto completado
        if ($estadoNuevo == 4) {
            return true;
        }

        // ✔ Progresión lineal
        $permitidas = array(
            1 => array(2, 4),
            2 => array(3, 4),
            3 => array(5, 4)
        );

        return in_array($estadoNuevo, isset($permitidas[$estadoActual]) ? $permitidas[$estadoActual] : array());
    }


    public function obtenerSiguienteEstado($estadoActual) {

        // si ya está finalizado, no hay siguiente estado
        if ($estadoActual == 5) {
            return null;
        }

        // rechazo siempre permitido (lo manejas aparte)
        $rechazo = 4;

        $flujo = array(
            1 => 2,
            2 => 3,
            3 => 5
        );

        return array(
            'siguiente' => isset($flujo[$estadoActual]) ? $flujo[$estadoActual] : null,
            'rechazo'   => $rechazo
        );
    }


    public function obtenerFuncionarioResponsable($idSolicitud) {

        $sql = "
            SELECT id_usuario_respuesta
            FROM respuestas_solicitud
            WHERE id_solicitud = $1
            ORDER BY id_respuesta ASC
            LIMIT 1
        ";

        $result = $this->query($sql, array($idSolicitud));

        $row = pg_fetch_assoc($result);

        return isset($row['id_usuario_respuesta']) ? $row['id_usuario_respuesta'] : null;
    }

    public function esFuncionarioResponsable($idSolicitud, $idUsuario) {

        $responsable = $this->obtenerFuncionarioResponsable($idSolicitud);

        return $responsable == $idUsuario;
    }




    public function registrarAuditoria(
        $idSolicitud,
        $idUsuarioSolicitante,
        $idUsuarioEjecutor,
        $idEstado,
        $mensaje
    ) {

        $sql = "
            INSERT INTO auditoria_solicitudes (
                id_solicitud,
                id_usuario,
                id_usuario_ejecutor,
                id_estado_solicitud,
                mensaje,
                fecha
            )
            VALUES ($1,$2,$3,$4,$5,NOW())
        ";

        return $this->query($sql, array(
            $idSolicitud,
            $idUsuarioSolicitante,
            $idUsuarioEjecutor,
            $idEstado,
            $mensaje
        ));
    }

    public function envioReportes_o_Solicitudes(
        $id_usuario,
        $id_estado_solicitud,
        $id_tipo_solicitud,
        $descripcion,
        $direccion,
        $imagen_url,
        $id_causa_accidente = null,
        $id_tipo_senal = null,
        $id_categoria = null,
        $id_tipo_danio = null,
        $id_orientacion = null,
        $id_tipo_reductor = null,
        $id_tipo_pqrsf = null,
        $latitud = null,
        $longitud = null,
        $vehiculos = array(),
        $lesionados = array()
    ) {
        try {

            $this->beginTransaction();

            $sqlTipo = "
                SELECT codigo
                FROM tipos_solicitud
                WHERE id_tipo_solicitud = $1
            ";

            $resTipo = $this->query($sqlTipo, array($id_tipo_solicitud));
            if (!$resTipo || pg_num_rows($resTipo) === 0) {
                throw new Exception("Tipo de solicitud inválido.");
            }

            $rowTipo = pg_fetch_assoc($resTipo);
            $codigo = isset($rowTipo['codigo']) ? $rowTipo['codigo'] : null;
            if (empty($codigo)) {
                throw new Exception("Tipo de solicitud inválido.");
            }

            //    PQRSF no las envía, otros tipos pueden omitirlas
            // =====================================================
            $coordenadas = null;

            if ($latitud !== null && $longitud !== null && $latitud !== '' && $longitud !== '') {
                if (!is_numeric($latitud) || !is_numeric($longitud)) {
                    error_log("DEBUG: coordenadas inválidas recibidas, usando coordenadas por defecto para depuración.");
                    $coordenadas = 'POINT(-74.0721 4.7110)';
                } else {
                    $lat = (float)$latitud;
                    $lng = (float)$longitud;

                    if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
                        error_log("DEBUG: coordenadas fuera de rango, usando coordenadas por defecto para depuración.");
                        $coordenadas = 'POINT(-74.0721 4.7110)';
                    } else {
                        $wkt = "POINT($lng $lat)";

                        if (preg_match('/^POINT\(-?\d+(\.\d+)? -?\d+(\.\d+)?\)$/', $wkt)) {
                            $coordenadas = $wkt;
                            error_log("COORDENADAS OK: " . $wkt);
                        } else {
                            error_log("DEBUG: coordenadas no tienen formato válido, usando coordenadas por defecto para depuración.");
                            $coordenadas = 'POINT(-74.0721 4.7110)';
                        }
                    }
                }
            } else {
                error_log("DEBUG: sin coordenadas proporcionadas, usando coordenadas por defecto para depuración.");
                $coordenadas = 'POINT(-74.0721 4.7110)';
            }

            // Normalizar valores obligatorios y opcionales
            $descripcion = trim(isset($descripcion) ? $descripcion : '');
            if ($descripcion === '') {
                $descripcion = 'N/A';
            }

            $direccion = trim(isset($direccion) ? $direccion : '');
            if ($direccion === '') {
                $direccion = 'N/A';
            }

            $imagen_url = trim(isset($imagen_url) ? $imagen_url : '');
            if ($imagen_url === '') {
                $imagen_url = 'N/A';
            }
            error_log("DEBUG MODELO: imagen_url después de procesamiento = " . var_export($imagen_url, true));

            $id_causa_accidente = intval(isset($id_causa_accidente) ? $id_causa_accidente : 0);
            $id_tipo_senal = intval(isset($id_tipo_senal) ? $id_tipo_senal : 0);
            $id_categoria = intval(isset($id_categoria) ? $id_categoria : 0);
            $id_tipo_danio = intval(isset($id_tipo_danio) ? $id_tipo_danio : 0);
            $id_orientacion = intval(isset($id_orientacion) ? $id_orientacion : 0);
            $id_tipo_reductor = intval(isset($id_tipo_reductor) ? $id_tipo_reductor : 0);
            $id_tipo_pqrsf = intval(isset($id_tipo_pqrsf) ? $id_tipo_pqrsf : 0);

            // =====================================================
            // 3. INSERT PRINCIPAL EN solicitudes
            // =====================================================
            $sql = "
                INSERT INTO solicitudes (
                    id_usuario,
                    id_estado_solicitud,
                    id_tipo_solicitud,
                    descripcion,
                    direccion,
                    coordenadas,
                    imagen_url
                )
                VALUES (
                    $1, $2, $3, $4, $5,
                    ST_SetSRID(ST_GeomFromText($6::text), 4326),
                    $7
                )
                RETURNING id_solicitud
            ";

            $params = array(
                $id_usuario,
                $id_estado_solicitud,
                $id_tipo_solicitud,
                $descripcion,
                $direccion,
                $coordenadas,
                $imagen_url
            );

            $res = $this->query($sql, $params);
            $row = pg_fetch_assoc($res);

            if (empty($row['id_solicitud'])) {
                throw new Exception("No se generó id_solicitud para la solicitud principal.");
            }

            $id_solicitud = $row['id_solicitud'];
            error_log("INSERT solicitudes OK — id: " . $id_solicitud);

            switch ($codigo) {
                case 'reporte_accidente':
                    if (empty($id_causa_accidente)) {
                        throw new Exception("Debe seleccionar la causa del accidente.");
                    }

                    $resReporte = $this->query(
                        "INSERT INTO solicitudes_reporte_accidentes
                        (id_solicitud, id_causa_accidente, observacion)
                        VALUES ($1, $2, $3)
                        RETURNING id_solicitud_reporte_accidente",
                        [$id_solicitud, $id_causa_accidente, $descripcion]
                    );

                    $rowReporte = pg_fetch_assoc($resReporte);
                    $idReporte  = isset($rowReporte['id_solicitud_reporte_accidente']) ? $rowReporte['id_solicitud_reporte_accidente'] : null;

                    if (empty($idReporte)) {
                        throw new Exception("No se generó id_solicitud_reporte_accidente.");
                    }

                    if (!empty($vehiculos) && is_array($vehiculos)) {
                        foreach ($vehiculos as $idVehiculo) {
                            if (is_numeric($idVehiculo)) {
                                $this->query(
                                    "INSERT INTO vehiculos
                                    (id_solicitud_reporte_accidente, id_tipo_vehiculo)
                                    VALUES ($1, $2)",
                                    [$idReporte, (int)$idVehiculo]
                                );
                            }
                        }
                    }

                    if (!empty($lesionados) && is_array($lesionados)) {
                        foreach ($lesionados as $lesionado) {
                            $nombre = trim(isset($lesionado['nombre']) ? $lesionado['nombre'] : '');
                            if (empty($nombre)) {
                                continue;
                            }

                            $resLes = $this->query(
                                "INSERT INTO lesionados
                                (nombre_completo, documento, observacion)
                                VALUES ($1, $2, $3)
                                RETURNING id_lesionado",
                                [
                                    $nombre,
                                    trim(isset($lesionado['documento']) ? $lesionado['documento'] : ''),
                                    trim(isset($lesionado['observacion']) ? $lesionado['observacion'] : '')
                                ]
                            );

                            $rowLes = pg_fetch_assoc($resLes);
                            $idLes  = isset($rowLes['id_lesionado']) ? $rowLes['id_lesionado'] : null;

                            if (!empty($idLes)) {
                                $this->query(
                                    "INSERT INTO reporte_lesionado
                                    (id_solicitud_reporte_accidente, id_lesionado)
                                    VALUES ($1, $2)",
                                    [$idReporte, $idLes]
                                );
                            }
                        }
                    }
                    break;

                case 'senal_mal_estado':
                    if (empty($id_tipo_senal) || empty($id_categoria) || empty($id_tipo_danio) || empty($id_orientacion)) {
                        throw new Exception("Faltan datos obligatorios para señal en mal estado.");
                    }

                    $this->query(
                        "INSERT INTO solicitudes_senal_mal_estado
                        (id_solicitud, id_tipo_senal, id_categoria, id_tipo_danio, id_orientacion)
                        VALUES ($1, $2, $3, $4, $5)",
                        [$id_solicitud, $id_tipo_senal, $id_categoria, $id_tipo_danio, $id_orientacion]
                    );
                    break;

                case 'nueva_senalizacion':
                    if (empty($id_tipo_senal) || empty($id_categoria) || empty($id_orientacion)) {
                        throw new Exception("Faltan datos obligatorios para nueva señalización.");
                    }

                    $this->query(
                        "INSERT INTO solicitudes_nueva_senalizacion
                        (id_solicitud, id_tipo_senal, id_categoria, id_orientacion)
                        VALUES ($1, $2, $3, $4)",
                        [$id_solicitud, $id_tipo_senal, $id_categoria, $id_orientacion]
                    );
                    break;

                case 'reductor_mal_estado':
                    if (empty($id_tipo_reductor) || empty($id_categoria) || empty($id_tipo_danio)) {
                        throw new Exception("Faltan datos obligatorios para reductor en mal estado.");
                    }

                    $this->query(
                        "INSERT INTO solicitudes_reductor_mal_estado
                        (id_solicitud, id_tipo_reductor, id_categoria, id_tipo_danio)
                        VALUES ($1, $2, $3, $4)",
                        [$id_solicitud, $id_tipo_reductor, $id_categoria, $id_tipo_danio]
                    );
                    break;

                case 'nuevo_reductor':
                    if (empty($id_tipo_reductor) || empty($id_categoria)) {
                        throw new Exception("Faltan datos obligatorios para nuevo reductor.");
                    }

                    $this->query(
                        "INSERT INTO solicitudes_nuevo_reductor
                        (id_solicitud, id_categoria, id_tipo_reductor)
                        VALUES ($1, $2, $3)",
                        [$id_solicitud, $id_categoria, $id_tipo_reductor]
                    );
                    break;

                case 'via_publica_mal_estado':
                    if (empty($id_tipo_danio)) {
                        throw new Exception("Falta el tipo de daño para vía pública en mal estado.");
                    }

                    $this->query(
                        "INSERT INTO solicitudes_via_publica_mal_estado
                        (id_solicitud, id_tipo_danio)
                        VALUES ($1, $2)",
                        [$id_solicitud, $id_tipo_danio]
                    );
                    break;

                case 'pqrsf':
                    if (empty($id_tipo_pqrsf)) {
                        throw new Exception("Debe seleccionar el tipo de PQRSF.");
                    }

                    $this->query(
                        "INSERT INTO solicitudes_pqrsf
                        (id_solicitud, id_tipo_pqrsf, mensaje)
                        VALUES ($1, $2, $3)",
                        [$id_solicitud, $id_tipo_pqrsf, $descripcion]
                    );
                    break;

                default:
                    throw new Exception("Tipo de solicitud no soportado: " . $codigo);
            }

            $this->commit();

            error_log("SOLICITUD CREADA ID: " . $id_solicitud);
            return $id_solicitud;

        } catch (Exception $e) {
            try {
                $this->rollback();
            } catch (Exception $rollbackException) {
                error_log("ERROR AL HACER ROLLBACK: " . $rollbackException->getMessage());
            }

            error_log("EXCEPCIÓN envioReportes_o_Solicitudes: " . $e->getMessage());
            throw $e;
        }
    }








    public function consultarCausasAccidente() {

        $sql = "
            SELECT
                ca.id_causa_accidente,
                ca.id_tipo_choque,
                tc.nombre_tipo_choque,
                ca.nombre_causa
            FROM causas_accidente ca
            INNER JOIN tipos_choque tc
                ON ca.id_tipo_choque = tc.id_tipo_choque
            ORDER BY
                tc.nombre_tipo_choque,
                ca.nombre_causa
        ";

        return $this->query($sql);
    }

    public function consultarTiposSenal() {

        $sql = "
            SELECT
                id_tipo_senal,
                nombre_tipo_senal
            FROM tipos_senal
            ORDER BY nombre_tipo_senal
        ";

        return $this->query($sql);
    }

    public function consultarCategorias() {

        $sql = "
            SELECT
                c.id_categoria,
                c.id_tipo_senal,
                c.nombre_categoria,
                c.descripcion_categoria
            FROM categorias c
            ORDER BY
                c.id_tipo_senal,
                c.id_categoria;
        ";

        return $this->query($sql);
    }

    public function consultarOrientaciones() {

        $sql = "
            SELECT
                id_orientacion,
                nombre_orientacion
            FROM orientaciones
            ORDER BY nombre_orientacion
        ";

        return $this->query($sql);
    }

    public function consultarTiposDanio() {

        $sql = "
            SELECT
                id_tipo_danio,
                nombre_tipo_danio,
                descripcion_danio
            FROM tipos_danio
            ORDER BY nombre_tipo_danio
        ";

        return $this->query($sql);
    }

    public function consultarTiposReductor() {

        $sql = "
            SELECT
                id_tipo_reductor,
                nombre_tipo_reductor,
                descripcion
            FROM tipos_reductor
            ORDER BY nombre_tipo_reductor
        ";

        return $this->query($sql);
    }

    public function consultarTiposPQRSF() {

        $sql = "
            SELECT
                id_tipo_pqrsf,
                tipo_pqrsf
            FROM tipos_pqrsf
            ORDER BY tipo_pqrsf
        ";

        return $this->query($sql);
    }

    public function consultarTiposChoque() {

        $sql = "
            SELECT
                id_tipo_choque,
                nombre_tipo_choque
            FROM tipos_choque
            ORDER BY nombre_tipo_choque
        ";

        return $this->query($sql);
    }

    public function consultarTiposVehiculo() {

        $sql = "
            SELECT
                id_tipo_vehiculo,
                nombre_vehiculo
            FROM tipos_vehiculo
            ORDER BY nombre_vehiculo
        ";

        return $this->query($sql);
    }




    public function obtenerIdTipoSolicitud($codigo) {
        $sql = "
            SELECT id_tipo_solicitud
            FROM tipos_solicitud
            WHERE codigo = $1
        ";

        $row = $this->queryOne($sql, array($codigo));
        return isset($row['id_tipo_solicitud']) ? $row['id_tipo_solicitud'] : null;
    }


    public function obtenerAuditoriaPorSolicitud($idSolicitud) {

        $sql = "
            SELECT
                a.fecha,
                a.mensaje,
                es.nombre_estado_solicitud AS estado,

                CONCAT(
                    u.primer_nombre,
                    COALESCE(' ' || u.segundo_nombre, ''),
                    ' ',
                    u.primer_apellido,
                    COALESCE(' ' || u.segundo_apellido, '')
                ) AS nombre_funcionario

            FROM auditoria_solicitudes a

            LEFT JOIN usuarios u
                ON u.id_usuario = a.id_usuario_ejecutor

            LEFT JOIN estados_solicitud es
                ON es.id_estado_solicitud = a.id_estado_solicitud

            WHERE a.id_solicitud = $1
            AND a.id_usuario_ejecutor IS NOT NULL

            ORDER BY a.fecha DESC
        ";

        $resultado = $this->query($sql, array($idSolicitud));

        $auditorias = array();

        while ($fila = pg_fetch_assoc($resultado)) {
            $auditorias[] = $fila;
        }

        return $auditorias;
    }

    }