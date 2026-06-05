<?php
require_once __DIR__ . '/../MasterModel.php';

class PerfilModel extends MasterModel
{
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
