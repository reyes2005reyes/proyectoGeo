<?php
include_once '../lib/helpersLogin.php';

class ManualesController {

    public function index() {

        if (!estaLogueado()) {
            redirect('/proyectoGeo/web/login.php');
            return;
        }

        if (!tienePermiso('Manuales', 'listar')) {
            $_SESSION['error'] = 'No tiene permisos para acceder a esta sección.';
            redirect('/proyectoGeo/web/index.php');
            return;
        }

        include_once dirname(__FILE__) . '/../../view/manuales/manuales.php';
    }

}
?>