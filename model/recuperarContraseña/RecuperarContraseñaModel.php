<?php
include_once '../model/MasterModel.php';

class RecuperarContraseñaModel extends MasterModel {

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
}
?>