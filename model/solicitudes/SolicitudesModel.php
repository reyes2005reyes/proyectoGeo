<?php

require_once  dirname(__FILE__) . '/../MasterModel.php';

class SolicitudesModel extends MasterModel {
    // Función para convertir el resultado de la consulta en un array
    private function resultadoAArray($resultado) {
        $datos = array();
        while ($fila = pg_fetch_assoc($resultado)) {
            $datos[] = $fila;
        }

        return $datos;
    }
    // Función para obtener los catálogos necesarios para el formulario de solicitud
    public function obtenerCatalogosFormulario() {
        $catalogos = array();
        $catalogos['causas_accidente'] = $this->resultadoAArray(
            $this->select("
                SELECT
                    ca.id_causa_accidente,
                    ca.nombre_causa,
                    tc.nombre_tipo_choque
                FROM causas_accidente ca
                INNER JOIN tipos_choque tc
                    ON ca.id_tipo_choque = tc.id_tipo_choque
                ORDER BY ca.nombre_causa
            ")
        );

        $catalogos['tipos_vehiculo'] = $this->resultadoAArray(
            $this->select("
                SELECT *
                FROM tipos_vehiculo
                ORDER BY nombre_vehiculo
            ")
        );

        $catalogos['tipos_senal'] = $this->resultadoAArray(
            $this->select("
                SELECT *
                FROM tipos_senal
                ORDER BY nombre_tipo_senal
            ")
        );

        $catalogos['categorias'] = $this->resultadoAArray(
            $this->select("
                SELECT
                    id_categoria,
                    id_tipo_senal,
                    nombre_categoria
                FROM categorias
                ORDER BY nombre_categoria
            ")
        );

        $catalogos['categorias_reductor'] = $this->resultadoAArray(
            $this->select("
                SELECT
                    id_categoria_reductor,
                    nombre_categoria
                FROM categorias_reductor
                ORDER BY nombre_categoria
            ")
        );

        $catalogos['senales'] = $this->resultadoAArray(
            $this->select("
                SELECT
                    id_senal,
                    id_categoria,
                    codigo,
                    nombre_senal,
                    descripcion
                FROM senales
                ORDER BY codigo
            ")
        );

        $catalogos['orientaciones'] = $this->resultadoAArray(
            $this->select("
                SELECT *
                FROM orientaciones
                ORDER BY nombre_orientacion
            ")
        );

        $catalogos['tipos_danio'] = $this->resultadoAArray(
            $this->select("
                SELECT *
                FROM tipos_danio
                ORDER BY nombre_tipo_danio
            ")
        );

        $catalogos['tipos_danio_senal'] = $this->resultadoAArray(
            $this->select("
                SELECT id_tipo_danio, nombre_tipo_danio
                FROM tipos_danio
                WHERE id_tipo_danio IN (1, 2, 3, 4, 5, 6)
                ORDER BY id_tipo_danio
            ")
        );

        $catalogos['tipos_danio_via'] = $this->resultadoAArray(
            $this->select("
                SELECT id_tipo_danio, nombre_tipo_danio
                FROM tipos_danio
                WHERE id_tipo_danio IN (7, 8, 9, 10, 11)
                ORDER BY id_tipo_danio
            ")
        );

        $catalogos['tipos_danio_reductor'] = $this->resultadoAArray(
            $this->select("
                SELECT id_tipo_danio, nombre_tipo_danio
                FROM tipos_danio
                WHERE id_tipo_danio IN (12, 13, 14, 15)
                ORDER BY id_tipo_danio
            ")
        );

        $catalogos['tipos_reductor'] = $this->resultadoAArray(
            $this->select("
                SELECT
                    id_tipo_reductor,
                    id_categoria_reductor,
                    nombre_tipo_reductor
                FROM tipos_reductor
                ORDER BY nombre_tipo_reductor
            ")
        );

        $catalogos['tipos_pqrsf'] = $this->resultadoAArray(
            $this->select("
                SELECT *
                FROM tipos_pqrsf
                ORDER BY tipo_pqrsf
            ")
        );

        return $catalogos;
    }

    // Función para obtener los tipos de solicitud disponibles
    public function obtenerTiposSolicitud() {
        $sql = "
            SELECT
                id_tipo_solicitud,
                codigo,
                nombre
            FROM tipos_solicitud
            ORDER BY nombre
        ";

        return $this->resultadoAArray(
            $this->select($sql)
        );
    }
    // Función para obtener los estados de solicitud disponibles
    public function obtenerEstadosSolicitud() {
        $sql = "
            SELECT
                id_estado_solicitud,
                nombre_estado_solicitud
            FROM estados_solicitud
            ORDER BY id_estado_solicitud
        ";
        return $this->select($sql);
    }
    // Función para listar las solicitudes con filtros opcionales
    public function listarSolicitudes($id_usuario = null, $fecha_inicio = null, $fecha_fin = null) {
        $sql = "
            SELECT
                s.id_solicitud,
                ts.nombre AS nombre_tipo_solicitud,
                es.nombre_estado_solicitud,
                s.direccion,
                s.fecha_solicitud,
                u.primer_nombre,
                u.primer_apellido
            FROM solicitudes s
            INNER JOIN tipos_solicitud ts ON s.id_tipo_solicitud = ts.id_tipo_solicitud
            INNER JOIN estados_solicitud es ON s.id_estado_solicitud = es.id_estado_solicitud
            INNER JOIN usuarios u ON s.id_usuario = u.id_usuario
            WHERE 1=1
        ";
        // Aplicar filtros si se proporcionan
        if ($id_usuario != null) {
            $sql .= " AND s.id_usuario = $id_usuario";
        }
        // Aplicar filtros de fecha si se proporcionan
        if (!empty($fecha_inicio)) {
            $fecha_inicio = pg_escape_string($fecha_inicio);
            $sql .= " AND s.fecha_solicitud::DATE >= '$fecha_inicio'";
        }
        // Aplicar filtros de fecha si se proporcionan
        if (!empty($fecha_fin)) {
            $fecha_fin = pg_escape_string($fecha_fin);
            $sql .= " AND s.fecha_solicitud::DATE <= '$fecha_fin'";
        }
        $sql .= " ORDER BY s.fecha_solicitud DESC";
        return $this->select($sql);
    }
    // Función para obtener los detalles de una solicitud específica
    public function obtenerSolicitud($id_solicitud) {
        $sql = "
            SELECT
                s.*,
                ST_X(s.coordenadas) AS coord_x,
                ST_Y(s.coordenadas) AS coord_y,
                ts.nombre AS nombre_tipo_solicitud,
                ts.codigo,
                es.nombre_estado_solicitud,
                u.primer_nombre,
                u.primer_apellido,
                sen.codigo AS senal_codigo,
                sen.nombre_senal AS senal_nombre
            FROM solicitudes s
            INNER JOIN tipos_solicitud ts
                ON s.id_tipo_solicitud = ts.id_tipo_solicitud
            INNER JOIN estados_solicitud es
                ON s.id_estado_solicitud = es.id_estado_solicitud
            INNER JOIN usuarios u
                ON s.id_usuario = u.id_usuario
            LEFT JOIN solicitudes_senal_mal_estado ssme
                ON s.id_solicitud = ssme.id_solicitud
            LEFT JOIN solicitudes_nueva_senalizacion sns
                ON s.id_solicitud = sns.id_solicitud
            LEFT JOIN senales sen
                ON sen.id_senal = COALESCE(ssme.id_senal, sns.id_senal)
            WHERE s.id_solicitud = $id_solicitud
        ";
        return $this->select($sql);
    }

    // Función para obtener los detalles específicos de una solicitud según su tipo
    public function obtenerTipoSolicitud($id_tipo_solicitud) {
        $sql = "
            SELECT
                id_tipo_solicitud,
                codigo,
                nombre
            FROM tipos_solicitud
            WHERE id_tipo_solicitud = $id_tipo_solicitud
        ";

        return $this->select($sql);
    }

    // Función para crear una nueva solicitud en la base de datos
    public function crearSolicitud($datos) {
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
                ".$datos['id_usuario'].",
                ".$datos['id_estado_solicitud'].",
                ".$datos['id_tipo_solicitud'].",
                '".$datos['descripcion']."',
                '".$datos['direccion']."',
                ST_SetSRID(
                    GeometryFromText('POINT(".$datos['coord_x']." ".$datos['coord_y'].")'),
                    4326
                ),
                '".$datos['imagen_url']."'
            )
            RETURNING id_solicitud
        ";
        return $this->select($sql);
    }
    // Función para crear el detalle de una solicitud según su tipo
    public function crearDetalleSolicitud($id_solicitud, $codigo_tipo, $datos) {

        switch ($codigo_tipo) {

            case 'reporte_accidente':

                // Registrar el detalle del accidente
                $sql = "
                    INSERT INTO solicitudes_reporte_accidentes (
                        id_solicitud,
                        id_causa_accidente
                    )
                    VALUES (
                        $id_solicitud,
                        ".$datos['id_causa_accidente']."
                    )
                    RETURNING id_solicitud_reporte_accidente
                ";

                $reporte = $this->select($sql);
                $reporte = pg_fetch_assoc($reporte);
                // Registrar vehículo involucrado
                $sqlVehiculo = "
                    INSERT INTO vehiculos (
                        id_solicitud_reporte_accidente,
                        id_tipo_vehiculo
                    )
                    VALUES (
                        ".$reporte['id_solicitud_reporte_accidente'].",
                        ".$datos['id_tipo_vehiculo']."
                    )
                ";
                // Ejecutar la inserción del vehículo
                $this->insert($sqlVehiculo);

                // Registrar cantidad de lesionados
                $sqlLesionado = "
                    INSERT INTO lesionados (
                        numero_lesionados
                    )
                    VALUES (
                        ".$datos['numero_lesionados']."
                    )
                    RETURNING id_lesionado
                ";
                $lesionado = $this->select($sqlLesionado);
                $lesionado = pg_fetch_assoc($lesionado);

                // Relacionar lesionados con el reporte
                $sqlRelacion = "
                    INSERT INTO reporte_lesionado (
                        id_solicitud_reporte_accidente,
                        id_lesionado
                    )
                    VALUES (
                        ".$reporte['id_solicitud_reporte_accidente'].",
                        ".$lesionado['id_lesionado']."
                    )
                ";
                $this->insert($sqlRelacion);
            break;
            case 'senal_mal_estado':
                // Registrar señal en mal estado
                $sql = "
                    INSERT INTO solicitudes_senal_mal_estado (
                        id_solicitud,
                        id_senal,
                        id_tipo_danio,
                        id_orientacion
                    )
                    VALUES (
                        $id_solicitud,
                        ".$datos['id_senal'].",
                        ".$datos['id_tipo_danio'].",
                        ".$datos['id_orientacion']."
                    )
                ";
                $this->insert($sql);
            break;

            case 'nueva_senalizacion':
                // Registrar nueva señalización
                $sql = "
                    INSERT INTO solicitudes_nueva_senalizacion (
                        id_solicitud,
                        id_senal,
                        id_orientacion
                    )
                    VALUES (
                        $id_solicitud,
                        ".$datos['id_senal'].",
                        ".$datos['id_orientacion']."
                    )
                ";

                $this->insert($sql);
            break;

            case 'reductor_mal_estado':

                // Registrar reductor en mal estado
                $sql = "
                    INSERT INTO solicitudes_reductor_mal_estado (
                        id_solicitud,
                        id_categoria_reductor,
                        id_tipo_reductor,
                        id_tipo_danio
                    )
                    VALUES (
                        $id_solicitud,
                        ".$datos['id_categoria_reductor'].",
                        ".$datos['id_tipo_reductor'].",
                        ".$datos['id_tipo_danio']."
                    )
                ";
                $this->insert($sql);
            break;

            case 'nuevo_reductor':
                // Registrar nuevo reductor
                $sql = "
                    INSERT INTO solicitudes_nuevo_reductor (
                        id_solicitud,
                        id_categoria_reductor,
                        id_tipo_reductor
                    )
                    VALUES (
                        $id_solicitud,
                        ".$datos['id_categoria_reductor'].",
                        ".$datos['id_tipo_reductor']."
                    )
                ";
                $this->insert($sql);
            break;
            case 'via_publica_mal_estado':
                // Registrar vía pública en mal estado
                $sql = "
                    INSERT INTO solicitudes_via_publica_mal_estado (
                        id_solicitud,
                        id_tipo_danio
                    )
                    VALUES (
                        $id_solicitud,
                        ".$datos['id_tipo_danio']."
                    )
                ";
                $this->insert($sql);
            break;
            case 'pqrsf':
                // Registrar detalle PQRSF
                $sql = "
                    INSERT INTO solicitudes_pqrsf (
                        id_solicitud,
                        id_tipo_pqrsf
                    )
                    VALUES (
                        $id_solicitud,
                        ".$datos['id_tipo_pqrsf']."
                    )
                ";
                $this->insert($sql);
            break;
        }
    }
    // Función para crear una solicitud completa, incluyendo su detalle según el tipo
    public function crearSolicitudCompleta($datos, $codigo_tipo) {
        $solicitud = $this->crearSolicitud($datos);
        // Obtener el id generado
        $solicitud = pg_fetch_assoc($solicitud);
        // Registrar el detalle según el tipo de solicitud
        $this->crearDetalleSolicitud(
            $solicitud['id_solicitud'],
            $codigo_tipo,
            $datos
        );

        // Retornar la solicitud creada
        return $solicitud;
    }
        // Función para cambiar el estado de una solicitud
    public function cambiarEstadoSolicitud($id_solicitud, $id_estado_solicitud) {

        // Actualizar el estado de una solicitud
        $sql = "
            UPDATE solicitudes
            SET id_estado_solicitud = $id_estado_solicitud
            WHERE id_solicitud = $id_solicitud
        ";

        return $this->update($sql);
    }
        // Función para registrar la respuesta de un funcionario a una solicitud
    public function registrarRespuesta(
        $id_solicitud,
        $id_usuario,
        $id_estado_solicitud,
        $mensaje
    ) {

        // Registrar respuesta de un funcionario
        $sql = "
            INSERT INTO respuestas_solicitud (
                id_solicitud,
                id_usuario_respuesta,
                id_estado_solicitud,
                mensaje
            )
            VALUES (
                $id_solicitud,
                $id_usuario,
                $id_estado_solicitud,
                '$mensaje'
            )
        ";

        return $this->insert($sql);
    }
        // Función para obtener todas las respuestas asociadas a una solicitud específica
    public function obtenerRespuestasSolicitud($id_solicitud) {

        // Consultar todas las respuestas asociadas a una solicitud
        $sql = "
            SELECT
                rs.id_respuesta,
                rs.mensaje,
                rs.fecha,
                es.nombre_estado_solicitud,
                u.primer_nombre,
                u.primer_apellido
            FROM respuestas_solicitud rs
            INNER JOIN estados_solicitud es
                ON rs.id_estado_solicitud = es.id_estado_solicitud
            INNER JOIN usuarios u
                ON rs.id_usuario_respuesta = u.id_usuario
            WHERE rs.id_solicitud = $id_solicitud
            ORDER BY rs.fecha DESC
        ";

        return $this->select($sql);
    }
    // Función para obtener los detalles de una solicitud junto con el correo del ciudadano que la realizó
    public function obtenerSolicitudConCorreo($id_solicitud) {

        // Trae los datos de la solicitud junto con el correo del ciudadano
        $sql = "
            SELECT
                s.id_solicitud,
                ts.nombre AS nombre_tipo_solicitud,
                u.primer_nombre,
                u.primer_apellido,
                u.correo
            FROM solicitudes s
            INNER JOIN tipos_solicitud ts
                ON s.id_tipo_solicitud = ts.id_tipo_solicitud
            INNER JOIN usuarios u
                ON s.id_usuario = u.id_usuario
            WHERE s.id_solicitud = $id_solicitud
        ";

        return $this->select($sql);
    }
    

}
?>
