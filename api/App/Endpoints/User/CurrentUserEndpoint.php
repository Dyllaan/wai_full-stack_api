<?php
/**
 * Allows the current user to be retrieved, edited and deleted
 * @package App\Endpoints\User
 * @author Louis Figes
 * @generated This class was created using Github Copilot
 */
namespace App\Endpoints\User;

use App\Classes\Models\User as User;

class CurrentUserEndpoint extends \App\Classes\Endpoint\UserEndpoint 
{
    public function __construct() 
    {
        parent::__construct();
        $this->requiresAuthOn(['GET', 'PUT', 'DELETE']);
        $this->setRequestMethods(['GET', 'PUT', 'POST', 'DELETE']);
        $fields = ['name', 'email', 'password'];
        $this->getPostAttributes()->addRequiredStrings($fields);
        $this->getPutAttributes()->addAllowedStrings($fields);
    }

    protected function processGET() 
    {
        $this->setResponseCode(200);
        $this->setResponse($this->getUser()->toArray());
    }

    protected function processPOST() 
    {
        $user = new \App\Classes\Models\User($this->getUserDb());
        $user->setName($this->getRequest()->getAttribute('name'));
        $user->setEmail($this->getRequest()->getAttribute('email'));
        $user->setPassword($this->getRequest()->getAttribute('password'));
        $user->register($this->getUserDb());
        $msg = ['message' => 'User registered successfully'];
        $rsp = array_merge($msg, $user->toArray());
        $this->setResponseCode(201);
        $this->setResponse($rsp);
    }

    protected function processPUT() 
    {
        $user = $this->getUser();
        $changeFlag = false;
        
        $attributes = $this->getRequest()->getAttributes();
        if($attributes === null || empty($attributes)) {
            throw new \App\ClientErrorException(400, ['message' => 'No attributes to edit']);
        }
        if($this->getRequest()->hasAttribute('name')) {
            $changeFlag = true;
            $user->setName($this->getRequest()->getAttribute('name'));
        } 
        if($this->getRequest()->hasAttribute('email')) {
            $changeFlag = true;
            $user->setEmail($this->getRequest()->getAttribute('email'));
        } 
        if($this->getRequest()->hasAttribute('password')) {
            $changeFlag = true;
            $user->setPassword($this->getRequest()->getAttribute('password'));
        }

        if($changeFlag) {
            $user->update();
        } else {
            throw new \App\ClientErrorException(400, ['message' => 'No attributes to edit']);
        }

        $response = ["message" => "Profile updated"];
        $response = array_merge($response, $user->toArray());
        $this->setResponse($response);
        $this->setResponseCode(201);

    }

    protected function processDELETE() 
    {
        $user = $this->getUser();
        $response = $user->delete();
        $this->setResponseCode(200);
        $this->setResponse($response);
    }
}