<?php
include_once '../model/registro/RegistroModel.php';

class RegistroController {

    public function postRegistrar() {
        $obj = new RegistroModel();

        if (empty($_POST['primer_nombre']) || empty($_POST['primer_apellido']) ||
            empty($_POST['numero_documento']) || empty($_POST['correo']) ||
            empty($_POST['telefono']) || empty($_POST['direccion']) ||
            empty($_POST['contrasena']) || empty($_POST['id_tipo_documento'])) {
            $_SESSION['error_registro'] = 'Existen campos obligatorios sin completar.';
            redirect('../view/registro/Registro.php');
            return;
        }

        if ($obj->existeDocumento($_POST['numero_documento'])) {
            $_SESSION['error_registro'] = 'El número de identificación ya se encuentra registrado.';
            redirect('../view/registro/Registro.php');
            return;
        }

        if ($obj->existeCorreo($_POST['correo'])) {
            $_SESSION['error_registro'] = 'El correo ya se encuentra asociado a una cuenta.';
            redirect('../view/registro/Registro.php');
            return;
        }

        $resultado = $obj->registrar($_POST);

        if ($resultado) {
            redirect('login.php');
        } else {
            $_SESSION['error_registro'] = 'No fue posible completar el registro. Intente nuevamente.';
            redirect('../view/registro/Registro.php');
        }
    }
}
?>