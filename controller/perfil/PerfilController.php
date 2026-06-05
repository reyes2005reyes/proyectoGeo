<?php
include_once '../model/perfil/PerfilModel.php';

class PerfilController
{
    public function ver()
    {
        if (!isset($_SESSION['id_usuario'])) {
            redirect('login.php');
            return;
        }

        $model = new PerfilModel();
        $perfil = $model->obtenerPerfil($_SESSION['id_usuario']);

        require_once __DIR__ . '/../../view/verPerfil/verPerfil.php';
    }

    public function actualizar()
    {
        if (!isset($_SESSION['id_usuario'])) {
            redirect('login.php');
            return;
        }

        $idUsuario = $_SESSION['id_usuario'];
        $camposObligatorios = [
            'id_tipo_documento',
            'numero_documento',
            'primer_nombre',
            'primer_apellido',
            'correo',
            'telefono',
            'direccion'
        ];

        foreach ($camposObligatorios as $campo) {
            if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
                $_SESSION['error_perfil'] = 'Existen campos obligatorios sin completar.';
                redirect('index.php?modulo=perfil&controlador=perfil&funcion=ver');
                return;
            }
        }

        if (!is_numeric($_POST['id_tipo_documento']) || !is_numeric($_POST['numero_documento']) || !is_numeric($_POST['telefono'])) {
            $_SESSION['error_perfil'] = 'Documento, tipo de documento y telefono deben tener valores validos.';
            redirect('index.php?modulo=perfil&controlador=perfil&funcion=ver');
            return;
        }

        if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_perfil'] = 'Ingresa un correo electronico valido.';
            redirect('index.php?modulo=perfil&controlador=perfil&funcion=ver');
            return;
        }

        try {
            $model = new PerfilModel();

            if ($model->documentoExisteEnOtroUsuario($_POST['numero_documento'], $idUsuario)) {
                $_SESSION['error_perfil'] = 'El numero de identificacion ya pertenece a otro usuario.';
                redirect('index.php?modulo=perfil&controlador=perfil&funcion=ver');
                return;
            }

            if ($model->correoExisteEnOtroUsuario($_POST['correo'], $idUsuario)) {
                $_SESSION['error_perfil'] = 'El correo electronico ya pertenece a otro usuario.';
                redirect('index.php?modulo=perfil&controlador=perfil&funcion=ver');
                return;
            }

            $datos = [
                'id_tipo_documento' => $_POST['id_tipo_documento'],
                'numero_documento' => $_POST['numero_documento'],
                'primer_nombre' => trim($_POST['primer_nombre']),
                'segundo_nombre' => trim($_POST['segundo_nombre'] ?? ''),
                'primer_apellido' => trim($_POST['primer_apellido']),
                'segundo_apellido' => trim($_POST['segundo_apellido'] ?? ''),
                'correo' => trim($_POST['correo']),
                'telefono' => $_POST['telefono'],
                'direccion' => trim($_POST['direccion'])
            ];

            $model->actualizarPerfil($idUsuario, $datos);

            $_SESSION['primer_nombre'] = $datos['primer_nombre'];
            $_SESSION['primer_apellido'] = $datos['primer_apellido'];
            $_SESSION['numero_documento'] = $datos['numero_documento'];
            $_SESSION['exito_perfil'] = 'Datos actualizados correctamente.';
        } catch (Exception $e) {
            $_SESSION['error_perfil'] = 'No fue posible actualizar los datos. Intente nuevamente.';
        }

        redirect('index.php?modulo=perfil&controlador=perfil&funcion=ver');
    }
}
?>
