<?php


require_once "../model/SolicitudDAO.php";

class SolicitudController {

    public function index() {

        $dao = new SolicitudDAO();
        $solicitudes = $dao->listarSolicitudes();

        require "./view/partials/solicitudes/vistaSolicitudes.php";
    }
}


?>