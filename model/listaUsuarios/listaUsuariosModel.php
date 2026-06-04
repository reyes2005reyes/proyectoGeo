<?php

include_once __DIR__ . '/../MasterModel.php';

class ListaUsuariosModel extends MasterModel {

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
}
?>