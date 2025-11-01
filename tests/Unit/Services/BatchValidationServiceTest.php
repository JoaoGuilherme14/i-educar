<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\BatchValidationService;
use ReflectionClass;

class BatchValidationServiceTest extends TestCase
{
    private $method;

    protected function setUp(): void
    {
        parent::setUp();
        $service = new BatchValidationService();
        $reflector = new ReflectionClass($service);
        $this->method = $reflector->getMethod('validateBatchParams');
        $this->method->setAccessible(true);
        $this->service = $service;
    }

    private function callValidate(array $params)
    {
        return $this->method->invoke($this->service, $params);
    }

    public function deve_retornar_parametros_validos_quando_tudo_correto()
    {
        $params = ['schools' => [1, 2], 'periodos' => [1, 2], 'year' => 2025];

        $result = $this->callValidate($params);

        $this->assertTrue($result['valid']);
        $this->assertEquals('Parâmetros válidos.', $result['message']);
    }

    public function deve_rejeitar_quando_escolas_vazias()
    {
        $params = ['schools' => [], 'periodos' => [1], 'year' => 2025];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Parâmetros de escola, períodos e ano letivo são obrigatórios.', $result['message']);
    }

    public function deve_rejeitar_quando_periodos_vazios()
    {
        $params = ['schools' => [1], 'periodos' => [], 'year' => 2025];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Parâmetros de escola, períodos e ano letivo são obrigatórios.', $result['message']);
    }

    public function deve_rejeitar_quando_ano_nao_informado()
    {
        $params = ['schools' => [1], 'periodos' => [1], 'year' => null];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Parâmetros de escola, períodos e ano letivo são obrigatórios.', $result['message']);
    }

    public function deve_rejeitar_quando_escolas_nao_for_array()
    {
        $params = ['schools' => "1,2,3", 'periodos' => [1,2], 'year' => 2025];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Parâmetros de escola e períodos devem ser arrays.', $result['message']);
    }

    public function deve_rejeitar_quando_periodos_nao_for_array()
    {
        $params = ['schools' => [1,2], 'periodos' => "manhã,tarde", 'year' => 2025];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Parâmetros de escola e períodos devem ser arrays.', $result['message']);
    }

    public function deve_rejeitar_quando_ano_nao_numerico()
    {
        $params = ['schools' => [1], 'periodos' => [1], 'year' => "abc"];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Ano letivo deve ser um número inteiro.', $result['message']);
    }

    public function deve_rejeitar_quando_ano_decimal()
    {
        $params = ['schools' => [1], 'periodos' => [1], 'year' => 2025.5];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Ano letivo deve ser um número inteiro.', $result['message']);
    }

    public function deve_aceitar_ano_string_numerica()
    {
        $params = ['schools' => [1], 'periodos' => [1], 'year' => "2025"];

        $result = $this->callValidate($params);

        $this->assertTrue($result['valid']);
        $this->assertEquals('Parâmetros válidos.', $result['message']);
    }

    public function deve_rejeitar_quando_todos_os_parametros_vazios()
    {
        $params = ['schools' => [], 'periodos' => [], 'year' => null];

        $result = $this->callValidate($params);

        $this->assertFalse($result['valid']);
        $this->assertEquals('Parâmetros de escola, períodos e ano letivo são obrigatórios.', $result['message']);
    }
}
