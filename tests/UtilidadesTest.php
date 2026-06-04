<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../model/Utilidades.php';

class UtilidadesTest extends TestCase
{
    public function testEsMayorDeEdad()
    {
        $util = new Utilidades();

        $this->assertTrue(
            $util->esMayorDeEdad(20)
        );
    }

    public function testNoEsMayorDeEdad()
    {
        $util = new Utilidades();

        $this->assertFalse(
            $util->esMayorDeEdad(15)
        );
    }
}