# Silex NFe.io Service Provider

[![Build Status](https://travis-ci.org/SilexFriends/NFe.svg?branch=master)](https://travis-ci.org/SilexFriends/NFe)
[![Code Climate](https://codeclimate.com/github/SilexFriends/NFe/badges/gpa.svg)](https://codeclimate.com/github/SilexFriends/NFe)
[![Test Coverage](https://codeclimate.com/github/SilexFriends/NFe/badges/coverage.svg)](https://codeclimate.com/github/SilexFriends/NFe/coverage)
[![Issue Count](https://codeclimate.com/github/SilexFriends/NFe/badges/issue_count.svg)](https://codeclimate.com/github/SilexFriends/NFe)

NFe.io Client for Silex

[x] Generate NFe
[x] Generate PDF
[x] Generate XML


## Install

```
composer install mrprompt/silex-nfe
```

## Use

```
use Silex\Application;
use SilexFriends\Nfe\Service;

$token   = getenv('NFE_TOKEN');
$company = getenv('NFE_COMPANY');

$app = new Application;
$app->register(new Service($token, $company));

// Create
$this->app['nfe.create']([
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
         'email' => 'seu@email.da.nota',
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
 ]);

// Generate PDF from Existent NFe
$app['nfe.pdf']('570ea6a4dfd7bc0af4cb55b3');

// Generate XML from Existent NFe
$app['nfe.xml']('570ea6a4dfd7bc0af4cb55b3');
```

## Test

Set environment variables:

- NFE_TOKEN
- NFE_COMPANY
- NFE_ID

Run tests

```
./vendor/bin/phpunit 
```

## License

MIT