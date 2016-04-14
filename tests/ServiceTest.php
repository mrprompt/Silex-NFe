<?php
namespace MrPrompt\Silex\NFe\Tests;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use MrPrompt\Silex\NFe\Service;

/**
 * NFe Service Test Case
 *
 * @author Thiago Paes <mrprompt@gmail.com>
 */
class ServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $app;

    /**
     * Bootstrap
     */
    public function setUp()
    {
        parent::setUp();

        $token   = getenv('NFE_TOKEN');
        $company = getenv('NFE_COMPANY');

        $app = new Application;
        $app->register(new Service($token, $company));

        $this->app = $app;
    }

    /**
     * Shutdown
     */
    public function tearDown()
    {
        $this->app = null;

        parent::tearDown();
    }

    /**
     * @test
     */
    public function createMustBeReturnErrorWhenReceiveEmptyFields()
    {
        $fields  = [];

        $validate = $this->app['nfe.create']($fields);

        $this->assertInstanceOf(\Nfe_ServiceInvoice::class, $validate);
    }

    /**
     * @test
     */
    public function createMustBeReturnObjectWhenValidFields()
    {
        $fields  = [
            // Código do serviço de acordo com o a cidade
            'cityServiceCode' => '2690',
            // Descrição dos serviços prestados
            'description' => 'TESTE EMISSAO',
            // Valor total do serviços
            'servicesAmount' => 0.01,
            // Dados do Tomador dos Serviços
            'borrower' => [
                // CNPJ ou CPF (opcional para tomadores no exterior)
                'federalTaxNumber' => 191,
                // Nome da pessoa física ou Razão Social da Empresa
                'name' => 'BANCO DO BRASIL SA',
                // Email para onde deverá ser enviado a nota fiscal
                'email' => 'hackers@nfe.io',
                // Endereço do tomador
                'address' => [
                    // Código do pais com três letras
                    'country' => 'BRA',
                    // CEP do endereço (opcional para tomadores no exterior)
                    'postalCode' => '70073901',
                    // Logradouro
                    'street' => 'Outros Quadra 1 Bloco G Lote 32',
                    // Número (opcional)
                    'number' => 'S/N',
                    // Complemento (opcional)
                    'additionalInformation' => 'QUADRA 01 BLOCO G',
                    // Bairro
                    'district' => 'Asa Sul',
                    // Cidade é opcional para tomadores no exterior
                    'city' => [
                        // Código do IBGE para a Cidade
                        'code' => '5300108',
                        // Nome da Cidade
                        'name' => 'Brasilia'
                    ],
                    // Sigla do estado (opcional para tomadores no exterior)
                    'state' => 'DF'
                ]
            ]
        ];

        $validate = $this->app['nfe.create']($fields);

        $this->assertInstanceOf(\Nfe_ServiceInvoice::class, $validate);
    }

    /**
     * @test
     */
    public function pdfMustBeReturnValidUrlWhenIdentifierIsValid()
    {
        $validate = $this->app['nfe.pdf']('570ea6a4dfd7bc0af4cb55b3');

        $this->assertNotEmpty($validate);
        $this->assertStringStartsWith('http', $validate);
    }

    /**
     * @test
     */
    public function pdfMustBeReturnFalseWhenInvalidValidIdentifier()
    {
        $validate   = $this->app['nfe.pdf']('aldldkfjdlaldkjd');

        $this->assertFalse($validate);
    }

    /**
     * @test
     */
    public function xmlMustBeReturnValidUrlWhenIdentifierIsValid()
    {
        $this->markTestIncomplete('Needs valid ID');
        
        $validate = $this->app['nfe.xml']('570ea6a4dfd7bc0af4cb55b3');

        $this->assertNotEmpty($validate);
        $this->assertStringStartsWith('http', $validate);
    }

    /**
     * @test
     */
    public function xmlMustBeReturnFalseWhenInvalidValidIdentifier()
    {
        $validate   = $this->app['nfe.xml']('aldldkfjdlaldkjd');

        $this->assertFalse($validate);
    }
}
