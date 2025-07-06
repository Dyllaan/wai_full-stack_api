<?php
/**
 * Logs the user in and provides a JWT token through Firebase
 * @package App\Endpoints\User
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 * Pass the email and password in the Authorization header
 */
namespace App\Endpoints\User;

use \App\ClientErrorException;

class TokenEndpoint extends \App\Classes\Endpoint\UserEndpoint 
{

    public function __construct() 
    {
        parent::__construct();
        $this->addRequestMethod('GET');
    }

    private function login($email, $password) 
    {
        $user = new \App\Classes\Models\User($this->getUserDb());
        $user->setEmail($email);
        $user->setPassword($password);
        $user->login();
        return $user;
    }
    
    private function validateUser() 
    {
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            throw new \App\ClientErrorException(401, ['message' => "No credentials provided"]);
        }

        if (empty(trim($_SERVER['PHP_AUTH_USER'])) || empty(trim($_SERVER['PHP_AUTH_PW']))) {
            throw new \App\ClientErrorException(401, ['message' => "No credentials provided"]);
        }
    }

    public function processGET() 
    {
        $this->validateUser();
        $user = $this->login($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

        $this->setResponse($user->toArray());
    }
}
