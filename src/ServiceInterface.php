<?php
namespace MrPrompt\Silex\NFe;

/**
 * Service Interface
 *
 * @author Thiago Paes <mrprompt@gmail.com>
 */
interface ServiceInterface
{
    /**
     * @param $companyId
     * @param $params
     * @return array
     */
    public function create($companyId, $params);

    /**
     * @param $companyId
     * @param $nfeId
     * @return string
     */
    public function pdf($companyId, $nfeId);

    /**
     * @param $companyId
     * @param $nfeId
     * @return string
     */
    public function xml($companyId, $nfeId);
}