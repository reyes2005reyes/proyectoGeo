<?php

require_once __DIR__ . "/../model/SolicitudDao.php";

class SolicitudController {

    public function index() {

        $dao = new SolicitudDao();
        $solicitudes = $dao->listarSolicitudes();

        require __DIR__ . "/../view/partials/vistaSolicitudes.php";
    }
}


?>