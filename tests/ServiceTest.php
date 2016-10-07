<?php
namespace SilexFriends\Tests\NFe;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use SilexFriends\NFe\Service;

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
            'cityServiceCode'   => '2690',
            'description'       => 'TESTE EMISSAO',
            'servicesAmount'    => 0.01,
            'borrower'          => [
                'federalTaxNumber'  => 191,
                'name'              => 'BANCO DO BRASIL SA',
                'email'             => 'nfe-io@mailinator.com',
                'address'           => [
                    'country'               => 'BRA',
                    'postalCode'            => '70073901',
                    'street'                => 'Outros Quadra 1 Bloco G Lote 32',
                    'number'                => 'S/N',
                    'additionalInformation' => 'QUADRA 01 BLOCO G',
                    'district'              => 'Asa Sul',
                    'state'                 => 'DF',
                    'city'                  => [
                        'code' => '5300108',
                        'name' => 'Brasilia'
                    ],
                ]
            ]
        ];

        $validate = $this->app['nfe.create']($fields);

        $this->assertInstanceOf(\Nfe_ServiceInvoice::class, $validate);

        return $validate;
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
        $this->markTestIncomplete();
        
        $validate   = $this->app['nfe.xml']('570ea6a4dfd7bc0af4cb55b3');

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

    /**
     * @test
     * @dataProvider validPostalCodes
     */
    public function addressMustBeReturnValidResponseWhenPostalCodeIsValid($postalCode)
    {
        $validate = $this->app['nfe.address']($postalCode);

        $this->assertNotEmpty($validate);
        $this->assertInstanceOf(\stdClass::class, $validate);
        $this->assertNotEmpty($validate->postalCode);
        $this->assertNotEmpty($validate->streetSuffix);
        $this->assertNotEmpty($validate->street);
        $this->assertNotEmpty($validate->district);
        $this->assertNotEmpty($validate->city);
        $this->assertNotEmpty($validate->state);
    }

    /**
     * @test
     * @dataProvider invalidPostalCodes
     * @expectedException \NfeObjectNotFound
     */
    public function addressMustBeReturnErrorResponseWhenPostalCodeIsInvalid($postalCode)
    {
        $validate = $this->app['nfe.address']($postalCode);

        $this->assertNotEmpty($validate);
        $this->assertInstanceOf(\NfeObjectNotFound::class, $validate);
    }

    /**
     * @return array
     */
    public function validPostalCodes()
    {
        return [
            [
                '88090-080',
            ],
            [
                '05761280'
            ]
        ];
    }

    /**
     * @return array
     */
    public function invalidPostalCodes()
    {
        return [
            [
                '88090',
            ],
            [
                '0'
            ],
            [
                'Morbi fringilla'
            ],
        ];
    }
}
