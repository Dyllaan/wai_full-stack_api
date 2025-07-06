<?php
namespace App\Endpoints;
/**
 * ContentTypesEndpoint is used to return the content types
 * this is used in the select dropdown on the frontend content page
 * @author Louis Figes
 * @package App\Endpoints
 */
class ContentTypesEndpoint extends \App\Classes\Endpoint\DBEndpoint 
{

    public function __construct() 
    {
        parent::__construct();
        $this->addRequestMethod('GET');
    }

    public function processGET() 
    {
        $select = $this->getDb()->createSelect()->select("type.name as type")->from('type');
        $this->setResponse($select->execute());
        $this->setResponseCode(200);
    }
}
