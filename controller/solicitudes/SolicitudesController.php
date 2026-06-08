<?php   
    include_once '../model/solicitudes/SolicitudesModel.php';
    
class SolicitudesController {

    public function reportar_accidente(){


         // pasar variables a la vista
        require_once __DIR__ . '/../../view/solicitudes/ReporteAccidente.php';

    }









































    public function reportar_senal_mal_estado(){
    
         // pasar variables a la vista
        require_once __DIR__ . '/../../view/solicitudes/SolicitudSeñalizaciónMalEstado.php';
    }
    








    public function listar() {
    
    

        require_once __DIR__ . '/../../view/solicitud/vistaSolicitudes.php';
    }
}
?>