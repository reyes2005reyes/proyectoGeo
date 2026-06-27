<?php
class AcercadeController {
    /**
     * Método que carga la vista de "Acerca de"
     */
    public function index() {
        require_once dirname(__FILE__) . '/../../view/acercade/acercade.php';
    }
}