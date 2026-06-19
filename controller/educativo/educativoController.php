<?php

class EducativoController
{

    public function catalogo()
    {
        include_once dirname(__FILE__) . '/../../view/educativo/catalogo.php';
    }

        public function reglamentarias()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $reglamentarias = require dirname(__FILE__) . '/data_reglamentarias.php';

        include_once dirname(__FILE__) . '/../../view/educativo/reglamentarias.php';
    }
}
?>