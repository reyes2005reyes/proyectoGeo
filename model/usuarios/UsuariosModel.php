<?php
require_once dirname(__FILE__) . '/../MasterModel.php';
    
class UsuariosModel extends MasterModel {
        // estas funciones son para las consulastas para el registro de un usuario y para verificar si el numero de documento o el correo ya existen en la base de datos
        public function registrar($datos) {
            $sql = "SELECT registrar_usuario($1,$2,$3,$4,$5,$6,$7,$8,$9,$10) AS id_usuario";

            $params = array(
                (int) $datos['id_tipo_documento'],
                trim($datos['primer_nombre']),
                trim(isset($datos['segundo_nombre']) ? $datos['segundo_nombre'] : ''),
                trim($datos['primer_apellido']),
                trim(isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : ''),
                (int) $datos['numero_documento'],
                trim($datos['correo']),
                (int) $datos['telefono'],
                trim($datos['direccion']),
                md5($datos['contrasena'])
            );
            $resultado = $this->query($sql, $params);
            if (!$resultado) {
                return false;
            }
            $fila = pg_fetch_assoc($resultado);
            return isset($fila['id_usuario']) ? $fila['id_usuario'] : false;
        }

        public function existeDocumento($numero_documento) {
            $sql = "SELECT existe_documento($1) AS existe";
            $resultado = $this->query($sql, array((int) $numero_documento));
            $fila = pg_fetch_assoc($resultado);
            return isset($fila['existe']) && $fila['existe'] === 't';
        }
        public function existeCorreo($correo) {
            $sql = "SELECT existe_correo($1) AS existe";
            $resultado = $this->query($sql, array(trim($correo)));
            $fila = pg_fetch_assoc($resultado);
            return isset($fila['existe']) && $fila['existe'] === 't';
        }
        public function tipo_documento($tipo_documento) {
            $sql = "SELECT obtener_tipo_documento($1) AS nombre_tipo_documento";
            $resultado = $this->query($sql, array((int) $tipo_documento));
            $fila = pg_fetch_assoc($resultado);
            return isset($fila['nombre_tipo_documento']) ? $fila['nombre_tipo_documento'] : null;
        }
    // aqui termina el registro del usuario




    // aqui comienza la funcion para enviar el correo de recuperacion de contraseña
    public function buscarUsuario($numero_documento, $correo) {
        $numero_documento = pg_escape_string($numero_documento);
        $correo = pg_escape_string($correo);
        $sql = "SELECT id_usuario FROM usuarios 
                WHERE numero_documento = '$numero_documento' 
                AND correo = '$correo'
                AND id_estado_usuario = 1";
        return $this->select($sql);
    }

    public function guardarCodigo($id_usuario, $codigo) {
        // Eliminar códigos anteriores del usuario
        $this->delete("DELETE FROM codigos_recuperacion WHERE id_usuario = $id_usuario");
        
        $sql = "INSERT INTO codigos_recuperacion (id_usuario, codigo, intentos, expira_en, usado)
                VALUES ($id_usuario, '$codigo', 0, NOW() + INTERVAL '15 minutes', FALSE)";
        return $this->insert($sql);
    }

    public function verificarCodigo($id_usuario, $codigo) {
        $codigo = pg_escape_string($codigo);
        $sql = "SELECT * FROM codigos_recuperacion 
                WHERE id_usuario = $id_usuario 
                AND codigo = '$codigo'
                AND usado = FALSE
                AND expira_en > NOW()";
        return $this->select($sql);
    }

    public function existeCodigo($id_usuario, $codigo) {
    $codigo = pg_escape_string($codigo);

    $sql = "SELECT *
            FROM codigos_recuperacion
            WHERE id_usuario = $id_usuario
            AND codigo = '$codigo'
            AND usado = FALSE";

        return $this->select($sql);
    }

    public function incrementarIntentos($id_usuario) {
        $sql = "UPDATE codigos_recuperacion 
                SET intentos = intentos + 1 
                WHERE id_usuario = $id_usuario AND usado = FALSE";
        return $this->update($sql);
    }

    public function getIntentos($id_usuario) {
        $sql = "SELECT intentos FROM codigos_recuperacion 
                WHERE id_usuario = $id_usuario AND usado = FALSE";
        $resultado = $this->select($sql);
        if (pg_num_rows($resultado) > 0) {
            $row = pg_fetch_assoc($resultado);
            return $row['intentos'];
        }
        return 0;
    }

    public function eliminarCodigo($id_usuario) {
        $sql = "DELETE FROM codigos_recuperacion WHERE id_usuario = $id_usuario";
        return $this->delete($sql);
    }

    public function marcarCodigoUsado($id_usuario) {
        $sql = "UPDATE codigos_recuperacion SET usado = TRUE 
                WHERE id_usuario = $id_usuario";
        return $this->update($sql);
    }

    public function actualizarContrasena($id_usuario, $nueva_contrasena) {
        $hash = md5($nueva_contrasena);
        $hash = pg_escape_string($hash);
        $sql = "UPDATE usuarios SET contrasena = '$hash' 
            WHERE id_usuario = $id_usuario";
        return $this->update($sql);
    }
    // aqui termina la funcion para enviar el correo de recuperacion de contraseña


    // aqui comienza la funcion para mostrar los usuarios registrados en el sistema
    public function obtenerUsuarios($numeroDocumento = '') {
        $numeroDocumento = trim($numeroDocumento);
        $condicion = '';

        if ($numeroDocumento !== '' && is_numeric($numeroDocumento)) {
            $numeroDocumento = pg_escape_string($numeroDocumento);
            $condicion = "WHERE u.numero_documento = $numeroDocumento";
        }

        $sql = "SELECT
                    u.id_usuario,
                    td.nombre_tipo_documento,
                    u.numero_documento,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.telefono,
                    r.nombre_rol,
                    eu.nombre_estado_usuario
                FROM usuarios u
                LEFT JOIN tipos_documento td ON u.id_tipo_documento = td.id_tipo_documento
                LEFT JOIN roles r ON u.id_rol = r.id_rol
                LEFT JOIN estados_usuario eu ON u.id_estado_usuario = eu.id_estado_usuario
                $condicion
                ORDER BY u.id_usuario DESC";

        $result = $this->select($sql);
        $usuarios = array();

        if ($result && pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }
    // fin aqui termina la funcion para mostrar los usuarios registrados en el sistema y editarlo xd



    // aqui comienza la funcion para mostrar el perfil del usuario y actualizarlo

    // Esta funcion si la utiliza el modulo miPerfil
    public function obtenerPerfil($idUsuario){
        $sql = "SELECT
                    u.id_usuario,
                    u.id_tipo_documento,
                    td.nombre_tipo_documento,
                    u.id_rol,
                    u.id_estado_usuario,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.numero_documento,
                    u.correo,
                    u.telefono,
                    u.direccion
                FROM usuarios u
                INNER JOIN tipos_documento td
                    ON u.id_tipo_documento = td.id_tipo_documento
                WHERE u.id_usuario = $idUsuario";

        $resultado = $this->select($sql);

        if(pg_num_rows($resultado) > 0){
            return pg_fetch_assoc($resultado);
        }

        return null;
    }

    // Fin
    
    public function documentoExisteEnOtroUsuario($numeroDocumento, $idUsuario){
    $numeroDocumento = (int) $numeroDocumento;

        if ($numeroDocumento <= 0) {
            return false;
        }

        $sql = "SELECT id_usuario
                FROM usuarios
                WHERE numero_documento = $numeroDocumento
                AND id_usuario <> $idUsuario";

        return pg_num_rows($this->select($sql)) > 0;
    }

    public function correoExisteEnOtroUsuario($correo, $idUsuario){
        $correo = pg_escape_string($correo);

        $sql = "SELECT id_usuario
                FROM usuarios
                WHERE correo = '$correo'
                AND id_usuario <> $idUsuario";

        return pg_num_rows($this->select($sql)) > 0;
    }

    // Esta funcion la utiliza el modulo usuarios
    public function actualizarPerfil($idUsuario, $datos){
        $primer_nombre = pg_escape_string($datos['primer_nombre']);
        $segundo_nombre = pg_escape_string(isset($datos['segundo_nombre']) ? $datos['segundo_nombre'] : '');
        $primer_apellido = pg_escape_string($datos['primer_apellido']);
        $segundo_apellido = pg_escape_string(isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : '');
        $correo = pg_escape_string($datos['correo']);
        $direccion = pg_escape_string($datos['direccion']);

        $sql = "UPDATE usuarios SET
            id_tipo_documento = {$datos['id_tipo_documento']},
            primer_nombre = '$primer_nombre',
            segundo_nombre = '$segundo_nombre',
            primer_apellido = '$primer_apellido',
            segundo_apellido = '$segundo_apellido',
            numero_documento = {$datos['numero_documento']},
            correo = '$correo',
            telefono = {$datos['telefono']},
            direccion = '$direccion'
            WHERE id_usuario = $idUsuario";

        return $this->update($sql);
    }


    //Funcion propia para actualizar los datos de miPerfil

    public function actualizarDatosPerfil($idUsuario, $correo, $telefono, $direccion){

        $correo = pg_escape_string($correo);
        $direccion = pg_escape_string($direccion);
        $telefono = $telefono;

        $sql = "UPDATE usuarios
                SET correo = '$correo',
                    telefono = $telefono,
                    direccion = '$direccion'
                WHERE id_usuario = $idUsuario";

        return $this->update($sql);
    }
    }

?>