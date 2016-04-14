<?php
namespace MrPrompt\Silex\NFe;

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
            return $this->create($this->company, $params);
        });

        $app['nfe.pdf'] = $app->protect(function($nfe) use ($company) {
            return $this->pdf($company, $nfe);
        });

        $app['nfe.xml'] = $app->protect(function($nfe) use ($company) {
            return $this->xml($company, $nfe);
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
        /* @var \Nfe_ServiceInvoice */
        $nfe = NFeService::create($companyId, $params);

        return $nfe;
    }

    /**
     * @inheritdoc
     */
    public function pdf($companyId, $nfeId)
    {
        $url = NFeService::pdf($companyId, $nfeId);

        return $url;
    }

    /**
     * @inheritdoc
     */
    public function xml($companyId, $nfeId)
    {
        $url = NFeService::xml($companyId, $nfeId);

        return $url;
    }
}
