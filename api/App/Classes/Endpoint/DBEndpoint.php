<?php
/**
 * DBEndpoint class, is the basis for endpoints which require database access.
 * @package App\Classes\Endpoint
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 * 
 * Only implements GET as the chi2023 database is read only
 */
namespace App\Classes\Endpoint;

use \App\ClientErrorException;
/**
 * DBEndpoint class, is the basis for endpoints which require database access.
 * 
 * @author Louis Figes W21017657
 * @generated Github Copilot was used during the creation of this code.
 */

abstract class DBEndpoint extends Endpoint 
{
    private $getAttributes;
    private $db;

    public function __construct() 
    {
        $this->setupDatabase();
        $this->getAttributes = new \App\Classes\Endpoint\Attributes\EndpointAttributes('GET');
    }

    public function process() {
        $this->validateGET();
        $this->processGET();
    }

    protected function processGET() {}
    
    /**
     * setDB is used to set the database object
     * @param $dbName
     * @return void
     */
    protected function setDB($dbName) 
    {
        $this->db = new \App\Database($dbName);
    }

    /**
     * sets up the chi2023 database
     * @return void
     */
    protected function setupDatabase() 
    {
        $this->setDB('chi2023.sqlite');
    }
    

    public function setRequest($request)
    {
        parent::setRequest($request);
    }

    private function validateGET() {
        $this->getGetAttributes()->validate($this->getRequest()->getAttributes());
    }

    public function getDb() {
        return $this->db;
    }

    public function getGetAttributes() {
        return $this->getAttributes;
    }

    protected function checkAndThrow($attrs, $message) {
        foreach ($attrs as $attr) {
            if (!$this->getRequest()->hasAttribute($attr) || empty($this->getRequest()->getAttribute($attr))) {
                throw new \App\ClientErrorException(400, ['message' => $message]);
            }
        }
    }
    
}