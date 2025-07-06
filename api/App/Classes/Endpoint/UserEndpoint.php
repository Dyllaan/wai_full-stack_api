<?php
/**
 * UserEndpoint is used to handle endpoints that require authentication
 * @package App\Classes\Endpoint
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Endpoint;

abstract class UserEndpoint extends DBEndpoint 
{

    /**
     * array: only put PUT, GET, POST AND DELETE in this array
     */
    private $requiresAuthOn = [];
    /**
     * @var int
     */
    private $user;
    /**
     * @var \App\Database
     */
    private $userDb;

    /**
    * Create arrays of endpoint attributes for each method, GET is created in DBEndpoint
    */
    private $postAttributes;
    private $putAttributes;
    private $deleteAttributes;

    /**
     * UserEndpoint constructor, sets the requiresAuth variable and creates an instance of the user database
     * also implements the post, put and delete methods
     * @param $requiresAuth
     */
    public function __construct() 
    {
        parent::__construct();
        $this->userDb = new \App\Database('users.sqlite');
        $this->postAttributes = new  \App\Classes\Endpoint\Attributes\EndpointAttributes('POST');
        $this->putAttributes = new  \App\Classes\Endpoint\Attributes\EndpointAttributes('PUT');
        $this->deleteAttributes = new  \App\Classes\Endpoint\Attributes\EndpointAttributes('DELETE');
    }

    /**
     * process is used to process the endpoint
     * @return void
     */
    public function process() 
    {
        $this->requiresAuth();
        $this->validateEach();
        $this->processCorrectMethod();
    }

    protected function processPOST() {}
    protected function processPUT() {}
    protected function processDELETE() {}

    public function processCorrectMethod() 
    {
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->processGET();
                break;
            case 'POST':
                $this->processPOST();
                break;
            case 'PUT':
                $this->processPUT();
                break;
            case 'DELETE':
                $this->processDELETE();
                break;
        }
    }

    private function validateEach() 
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->getPostAttributes()->validate($this->getRequest()->getAttributes());
                break;
            case 'PUT':
                $this->getPutAttributes()->validate($this->getRequest()->getAttributes());
                break;
            case 'DELETE':
                $this->getDeleteAttributes()->validate($this->getRequest()->getAttributes());
                break;
            case 'GET':
            default:
                $this->getGetAttributes()->validate($this->getRequest()->getAttributes());
                break;
        }
    }

    /**
     * requiresAuth is used to check if the endpoint requires authentication
     * @return void
     * @throws \App\ClientErrorException
     */
    public function requiresAuth() 
    {
        foreach ($this->requiresAuthOn as $method) {
            if ($_SERVER['REQUEST_METHOD'] === $method) {
                $this->getUser();
            }
        }
    }

    /**
     * getUser is used to get the user object from the token
     * @return int
     * @throws \App\ClientErrorException
     */
    public function getUser() 
    {
        if(!isset($this->user)) {
            $potentialUser = new \App\Classes\Models\User($this->getUserDb());
            if($potentialUser->verifyToken()) {
                $this->setUser($potentialUser);
            } else {
                throw new \App\ClientErrorException(401, ['message' => 'Invalid token']);
            }
        } else if(isset($this->user)) {
            return $this->user;
        } else {
            throw new \App\ClientErrorException(401, ['message' => 'Invalid token']);
        }
    }

    public function setUser($user) 
    {
        $this->user = $user;
    }

    public function getUserDb() 
    {
        return $this->userDb;
    }

    /**
     * pass array
     */
    protected function requiresAuthOn($arrayOfMethods) 
    {
        $this->requiresAuthOn = $arrayOfMethods;
    }

    protected function getRequiresAuth() 
    {
        return $this->requiresAuthOn;
    }

    protected function getPostAttributes() 
    {
        return $this->postAttributes;
    }

    protected function getPutAttributes() 
    {
        return $this->putAttributes;
    }

    protected function getDeleteAttributes() 
    {
        return $this->deleteAttributes;
    }
}