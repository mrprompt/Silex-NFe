<?php
namespace SilexFriends\NFe;

use Nfe as NFeClient;
use Nfe_ServiceInvoice as NFeService;
use Silex\ServiceProviderInterface;
use Silex\Application;

/**
 * NFe.io Service Provider
 *
 * @author Thiago Paes <mrprompt@gmail.com>
 */
class Service implements ServiceInterface, ServiceProviderInterface
{
    /**
     * API Token
     * @var string
     */
    private $token;

    /**
     * Company Identifier
     * @var string
     */
    private $company;

    /**
     * Service constructor.
     *
     * @param string $apiKey
     * @param string $companyId
     */
    public function __construct($apiKey, $companyId)
    {
        $this->token = $apiKey;
        $this->company = $companyId;

        NFeClient::setApiKey($apiKey);
    }

    /**
     * @inheritdoc
     */
    public function register(Application $app)
    {
        $company = $this->company;

        $app['nfe.create'] = $app->protect(function($params) use ($company) {
            return $this->create($company, $params);
        });

        $app['nfe.pdf'] = $app->protect(function($nfe) use ($company) {
            return $this->pdf($company, $nfe);
        });

        $app['nfe.xml'] = $app->protect(function($nfe) use ($company) {
            return $this->xml($company, $nfe);
        });

        $app['nfe.address'] = $app->protect(function($postalCode) use ($company) {
            return $this->address($postalCode);
        });
    }

    /**
     * @inheritdoc
     */
    public function boot(Application $app)
    {
        // none
    }

    /**
     * @inheritdoc
     */
    public function create($companyId, $params)
    {
        return NFeService::create($companyId, $params);
    }

    /**
     * @inheritdoc
     */
    public function pdf($companyId, $nfeId)
    {
        return NFeService::pdf($companyId, $nfeId);
    }

    /**
     * @inheritdoc
     */
    public function xml($companyId, $nfeId)
    {
        return NFeService::xml($companyId, $nfeId);
    }

    /**
     * @inheritdoc
     */
    public function address($postalCode)
    {
        $url = sprintf('http://open.nfe.io/v1/addresses/%s?api_key=%s', $postalCode, $this->token);

        return (new \Nfe_APIRequest())->request('GET', $url);
    }
}
