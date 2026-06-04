<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/MasterModel.php';
require_once __DIR__ . '/../model/registroUsuario/RegistroModel.php';

class RegistroModelTest extends TestCase
{
    public function testExisteCorreo()
    {
        $modelo = new RegistroModel();

        $this->assertTrue(
            $modelo->existeCorreo('admin@geo.gov.co')
        );
    }

    public function testExisteDocumento()
    {
        $modelo = new RegistroModel();

        $this->assertTrue(
            $modelo->existeDocumento(1023456789)
        );
    }
}

?>