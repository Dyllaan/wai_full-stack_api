<?php
/**
 * Token is used to handle the JWT token in an object oriented way
 * @package App\Classes\Models
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Models;

class Token 
{
  private $valid;
  private $userId;
  
  public function __construct() 
  {
    $this->valid = false;
    if($id = $this->validateToken()) {
        $this->valid = true;
        $this->userId = $id;
    }
  }

  /**
    * validateToken is used to validate the JWT token, from the Module code
    * @generated This function was created using Github Copilot
    * @return int
    * @throws \App\ClientErrorException
  */
  private function validateToken()
  {
    $key = SECRET;
    $authorizationHeader = $this->getAuthorizationHeaders();
    if (substr($authorizationHeader, 0, 7) != 'Bearer ') {
      throw new \App\ClientErrorException(401, ['message' => 'Not bearer token']);
    }
    $jwt = trim(substr($authorizationHeader, 7));

    try {
      $decodedJWT = \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key($key, 'HS256'));
      return $decodedJWT->id;
    } catch (\Firebase\JWT\ExpiredException $e) {
      throw new \App\ClientErrorException(401, ['message' => 'Token expired']);
    } catch (\Exception $e) {
      throw new \App\ClientErrorException(401, ['message' => 'Invalid token']);
    }
  }

  private function getAuthorizationHeaders()
  {
    $allHeaders = getallheaders();
    $authorizationHeader = "";
    if (array_key_exists('Authorization', $allHeaders)) {
      $authorizationHeader = $allHeaders['Authorization'];
    } elseif (array_key_exists('authorization', $allHeaders)) {
      $authorizationHeader = $allHeaders['authorization'];
    }
    return $authorizationHeader;
  }

  public function isValid() 
  {
    return $this->valid;
  }

  public function getUserId() 
  {
    return $this->userId;
  }
}