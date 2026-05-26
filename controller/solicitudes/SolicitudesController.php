<?php

require_once __DIR__ . '/../../model/solicitud/Solicitud.php';

class SolicitudesController {

    public function listar() {
        $model = new Solicitud();
        $solicitudes = $model->listarSolicitudes();

        require_once __DIR__ . '/../../view/solicitud/vistaSolicitudes.php';
    }
}
?>