<?php
    include_once '../model/acceso/AccesoModel.php';
    
    class AccesoController {

   public function login() {
    $obj = new AccesoModel();
    $numero_documento = $_POST['numero_documento'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM usuarios WHERE numero_documento = '$numero_documento'";
    $resultado = $obj->select($sql);

    if (pg_num_rows($resultado) > 0) {
        $usuario = pg_fetch_assoc($resultado);
        
        // Verifica la contraseña contra el hash
        if (password_verify($contrasena, $usuario['contrasena'])) {
            $_SESSION['primer_nombre'] = $usuario['primer_nombre'];
            $_SESSION['primer_apellido'] = $usuario['primer_apellido'];
            $_SESSION['numero_documento'] = $usuario['numero_documento'];
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['id_rol'] = $usuario['id_rol'];
            $_SESSION['auth'] = "ok";
    
            redirect('index.php');
        } else {
            $_SESSION['error'] = 'documento o contraseña incorrectos';
            redirect('login.php');
    }
    } else {
        $_SESSION['error'] = 'documento o contraseña incorrectos';
        redirect('login.php');
    }
}

        public function logout() {
            session_destroy();
            redirect('login.php');
        }
    
    }
?>