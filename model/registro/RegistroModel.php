<?php
    include_once '../model/MasterModel.php';
class RegistroModel extends MasterModel {

    public function registrar($datos) {
        $tipo_doc         = $datos['id_tipo_documento'];
        $primer_nombre    = pg_escape_string($datos['primer_nombre']);
        $segundo_nombre   = pg_escape_string($datos['segundo_nombre'] ?? '');
        $primer_apellido  = pg_escape_string($datos['primer_apellido']);
        $segundo_apellido = pg_escape_string($datos['segundo_apellido'] ?? '');
        $numero_documento = $datos['numero_documento'];
        $correo           = pg_escape_string($datos['correo']);
        $telefono         = $datos['telefono'];
        $direccion        = pg_escape_string($datos['direccion']);
        $contrasena       = password_hash($datos['contrasena'], PASSWORD_BCRYPT);

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
}
?>