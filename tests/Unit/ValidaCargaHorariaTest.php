<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../ieducar/intranet/Services/ValidaCargaHorariaService.php';

class ValidaCargaHorariaTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        // (confirmado pela estrutura padrão do i-Educar)
        $this->service = new ValidaCargaHorariaService();
    }

    public function testNaoDeveAceitarVinteEQuatroHorasComMinutosMaiorQueZero()
    {

        $resultado = $this->service->validaCargaHoraria('24:15');

        $this->assertFalse($resultado['valido']);
        $this->assertEquals(
            'Carga horária inválida. Se as horas forem 24, os minutos devem ser 00.',
            $resultado['mensagem']
        );
    }

    public function testDeveAceitarVinteEQuatroHorasComMinutosZero()
    {

        $resultado = $this->service->validaCargaHoraria('24:00');

        $this->assertTrue($resultado['valido']);
    }

    public function testValorComHorasInferioresA24NaoAplicaRegraEspecial()
    {

        $resultado = $this->service->validaCargaHoraria('23:59');

        $this->assertTrue($resultado['valido']);
    }


}
