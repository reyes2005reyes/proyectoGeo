<?php
require_once __DIR__ . '/../MasterModel.php'; 
    
class UsuariosModel extends MasterModel {
        // estas funciones son para las consulastas para el registro de un usuario y para verificar si el numero de documento o el correo ya existen en la base de datos
        public function registrar($datos) {
        $tipo_doc  = $datos['id_tipo_documento'];
        $primer_nombre = pg_escape_string($datos['primer_nombre']);
        $segundo_nombre = pg_escape_string($datos['segundo_nombre'] ?? '');
        $primer_apellido  = pg_escape_string($datos['primer_apellido']);
        $segundo_apellido = pg_escape_string($datos['segundo_apellido'] ?? '');
        $numero_documento = $datos['numero_documento'];
        $correo = pg_escape_string($datos['correo']);
        $telefono = $datos['telefono'];
        $direccion = pg_escape_string($datos['direccion']);
        $contrasena = password_hash($datos['contrasena'], PASSWORD_BCRYPT);

        $sql = "INSERT INTO usuarios (id_tipo_documento, id_rol, id_estado_usuario,
                primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                numero_documento, correo, telefono, direccion, contrasena)VALUES 
                ($tipo_doc, 3, 1,
                '$primer_nombre', '$segundo_nombre', '$primer_apellido', '$segundo_apellido',
                $numero_documento, '$correo', $telefono, '$direccion', '$contrasena')";

        return $this->insert($sql);
    }
    public function existeDocumento($numero_documento) {
        $sql = "SELECT id_usuario FROM usuarios WHERE numero_documento = $numero_documento";
        return pg_num_rows($this->select($sql)) > 0;
    }

    public function existeCorreo($correo) {
        $correo = pg_escape_string($correo);
        $sql = "SELECT id_usuario FROM usuarios WHERE correo = '$correo'";
        return pg_num_rows($this->select($sql)) > 0;
    }

    public function tipo_documento($tipo_documento) {
        $sql = "SELECT nombre_tipo_documento FROM tipos_documento WHERE id_tipo_documento = $tipo_documento";
        $resultado = $this->select($sql);
        if ($row = pg_fetch_assoc($resultado)) {
            return $row['nombre_tipo_documento'];
        }
        return null;
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
        $hash = password_hash($nueva_contrasena, PASSWORD_BCRYPT);
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
        $usuarios = [];

        if ($result && pg_num_rows($result) > 0) {
            while ($row = pg_fetch_assoc($result)) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }
    // fin aqui termina la funcion para mostrar los usuarios registrados en el sistema y editarlo xd



    // aqui comienza la funcion para mostrar el perfil del usuario y actualizarlo
    public function obtenerPerfil($idUsuario)
    {
        $sql = "SELECT
                    id_usuario,
                    id_tipo_documento,
                    id_rol,
                    id_estado_usuario,
                    primer_nombre,
                    segundo_nombre,
                    primer_apellido,
                    segundo_apellido,
                    numero_documento,
                    correo,
                    telefono,
                    direccion
                FROM usuarios
                WHERE id_usuario = $1";

        $resultado = $this->query($sql, [$idUsuario]);
        return pg_num_rows($resultado) > 0 ? pg_fetch_assoc($resultado) : null;
    }

    public function documentoExisteEnOtroUsuario($numeroDocumento, $idUsuario)
    {
        $sql = "SELECT id_usuario FROM usuarios WHERE numero_documento = $1 AND id_usuario <> $2";
        return pg_num_rows($this->query($sql, [$numeroDocumento, $idUsuario])) > 0;
    }

    public function correoExisteEnOtroUsuario($correo, $idUsuario)
    {
        $sql = "SELECT id_usuario FROM usuarios WHERE correo = $1 AND id_usuario <> $2";
        return pg_num_rows($this->query($sql, [$correo, $idUsuario])) > 0;
    }

    public function actualizarPerfil($idUsuario, $datos)
    {
        $sql = "UPDATE usuarios SET
                    id_tipo_documento = $1,
                    primer_nombre = $2,
                    segundo_nombre = $3,
                    primer_apellido = $4,
                    segundo_apellido = $5,
                    numero_documento = $6,
                    correo = $7,
                    telefono = $8,
                    direccion = $9
                WHERE id_usuario = $10";

        return $this->query($sql, [
            $datos['id_tipo_documento'],
            $datos['primer_nombre'],
            $datos['segundo_nombre'],
            $datos['primer_apellido'],
            $datos['segundo_apellido'],
            $datos['numero_documento'],
            $datos['correo'],
            $datos['telefono'],
            $datos['direccion'],
            $idUsuario
        ]);
    }

}

?>