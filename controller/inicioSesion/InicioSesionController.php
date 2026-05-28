<?php
    include_once '../model/inicioSesion/AccesoModel.php';
    
    class InicioSesionController {

   public function login() {
    try {
        $obj = new AccesoModel();
        // Error 1: Fallo en la conexión con la base de datos durante la validación de credenciales.
        if (!$obj->getConnect()) {
            $_SESSION['error'] = 'No es posible validar en este momento por un problema técnico. Intente más tarde.';
            redirect('login.php');
            return;
        }

        $numero_documento = $_POST['numero_documento'];
        $contrasena = $_POST['contrasena'];
        

        $resultado = @$obj->select("SELECT * FROM usuarios WHERE numero_documento = '$numero_documento'");
        
        // solo acepta numeros en el campo de documento, si ingresa String, le mostrara que solo acepta numeros, y no se ejecutara la consulta
        if (!is_numeric($numero_documento)) {
            $_SESSION['error'] = 'El número de identificación debe contener solo dígitos.';
            redirect('login.php');
            return;
        }

        if ($resultado === false) {
            $_SESSION['error'] = 'Tiempo de espera agotado. Verifique su conexión o intente nuevamente más tarde.';
            redirect('login.php');
            return;
        }

        if (pg_num_rows($resultado) > 0) {
            $usuario = pg_fetch_assoc($resultado);

            // Cuenta deshabilitada
            if ($usuario['id_estado_usuario'] != 1) {
                $_SESSION['error'] = 'La cuenta está inactiva o deshabilitada. Contacte al administrador del sistema.';
                redirect('login.php');
                return;
            }


            $verificacion = @password_verify($contrasena, $usuario['contrasena']);
            if ($verificacion) {
                $_SESSION['primer_nombre'] = $usuario['primer_nombre'];
                $_SESSION['primer_apellido'] = $usuario['primer_apellido'];
                $_SESSION['numero_documento'] = $usuario['numero_documento'];
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['id_rol'] = $usuario['id_rol'];
                $_SESSION['auth'] = "ok";
                $_SESSION['bienvenida'] = "Bienvenido, {$usuario['primer_nombre']} {$usuario['primer_apellido']}. Has iniciado sesión correctamente.";
                redirect('index.php');
            } else {
                $_SESSION['error'] = 'Documento o contraseña incorrectos.';
                redirect('login.php');
            }

        } else {
            $_SESSION['error'] = 'Documento o contraseña incorrectos.';
            redirect('login.php');
        }

    } catch (Exception $e) {
        // Error 3: Error interno del sistema durante el procesamiento de la solicitud de inicio de sesión.
        $_SESSION['error'] = 'Error inesperado. Estamos trabajando para solucionarlo.';
        redirect('login.php');
    }
}

        public function logout() {
            session_destroy();
            redirect('login.php');
        }
    
    }
?>