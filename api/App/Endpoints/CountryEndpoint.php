<?php
/**
 * CountryEndpoint is used to get a list of countries in the affiliation table
 * @package App\Endpoints
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Endpoints;

class CountryEndpoint extends \App\Classes\Endpoint\DBEndpoint 
{
    public function __construct() 
    {
        parent::__construct();
        $this->addRequestMethod('GET');
    }

    public function processGET() 
    {
        $this->setResponse($this->getDb()->createSelect()->select("DISTINCT country")->from('affiliation')->execute());
        $this->setResponseCode(200);
    }
}
