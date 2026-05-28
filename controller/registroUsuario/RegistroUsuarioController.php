<?php
include_once '../model/registroUsuario/RegistroModel.php';

class RegistroUsuarioController {
    public function postRegistrar() {
        try {
            // Error 1: Fallo en la conexión con la base de datos durante el almacenamiento de la información del usuario.
            $obj = new RegistroModel();
            if (!$obj->getConnect()) {
                $_SESSION['error_registro'] = 'No es posible completar el registro por un problema técnico. Intente nuevamente más tarde.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // El usuario deja uno o más campos obligatorios sin diligenciar
            if (empty($_POST['primer_nombre']) || empty($_POST['primer_apellido']) ||
                empty($_POST['numero_documento']) || empty($_POST['correo']) ||
                empty($_POST['telefono']) || empty($_POST['direccion']) ||
                empty($_POST['contrasena']) || empty($_POST['id_tipo_documento'])) {
                $_SESSION['error_registro'] = 'Existen campos obligatorios sin completar.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Criterio 1:  El usuario intenta registrarse con un número de identificación que ya existe en el sistema
            if ($obj->existeDocumento($_POST['numero_documento'])) {
                $_SESSION['error_registro'] = 'El número de identificación ingresado ya se encuentra registrado. Verifique la información e intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Criterio 2: El usuario intenta registrarse con un correo electrónico previamente registrado.
            if ($obj->existeCorreo($_POST['correo'])) {
                $_SESSION['error_registro'] = 'El correo electrónico ingresado ya se encuentra asociado a una cuenta existente. Verifique la información e intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Error 4:  Fallo en el proceso de almacenamiento o cifrado de la contraseña.
            $hash = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
            if (!$hash) {
                $_SESSION['error_registro'] = 'Ocurrió un error técnico durante la creación de la cuenta. Intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Error 2:  El servidor no responde durante el proceso de registro
            $resultado = @$obj->registrar($_POST);
            if ($resultado === false) {
                $_SESSION['error_registro'] = 'Tiempo de espera agotado. Verifique su conexión o intente nuevamente más tarde.';
                redirect('/proyectoGeo/view/registro/Registro.php');
                return;
            }

            // Criterio 5: Si el registro se realiza correctamente, el sistema debe crear la cuenta y mostrar el siguiente mensaje:
            if ($resultado) {
                $_SESSION['exito_registro'] = 'Registro realizado correctamente. Su cuenta ha sido creada exitosamente.';
                redirect('/proyectoGeo/web/login.php');
            } else {
                $_SESSION['error_registro'] = 'No fue posible completar el registro. Intente nuevamente.';
                redirect('/proyectoGeo/view/registro/Registro.php');
            }

        } catch (Exception $e) {
            // Error 3: error interno inesperado
            $_SESSION['error_registro'] = 'Error inesperado. Estamos trabajando para solucionarlo.';
            redirect('/proyectoGeo/view/registro/Registro.php');
        }
    }
}
?>