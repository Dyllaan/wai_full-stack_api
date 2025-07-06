<?php
/**
 * Request stores the request method and attributes so an endpoint can access them
 * @package App\Classes\HTTP
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\HTTP;
class Request
{
    /**
     * The attributes of the request, whatevere the request method is
     * @var array
     */
    private $attributes;
    /**
     * @var string
     */
    private $requestMethod;

    /**
     * Request constructor, sets the request method and attributes
     * @param $requestMethod
     */
    public function __construct($requestMethod) 
    {
        $this->requestMethod = $requestMethod;
        $this->parseRequest();
    }

    private function parseRequest()
    {
        switch($this->requestMethod) 
        {
            case 'GET':
            case 'DELETE':
                $this->attributes = $_GET;
                break;
            case 'POST':
            case 'PUT':
                $this->handleBody();
                break;
            default:
                throw new \App\ClientErrorException(405, ['message' => 'Method not allowed']);
        }
    }

    /**
     * handleBody is used to handle the body of the request, depending on the content type
     * @generated Github CoPilot was used during the creation of this code
     */
    private function handleBody()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
            /**
             * https://stackoverflow.com/questions/6041741/fastest-way-to-check-if-a-string-is-json-in-php
             * Authors:
             * User: Henrik P. Hessel
             * User: Your Common Sense
             */
            $json = file_get_contents('php://input');
            $this->attributes = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \App\ClientErrorException(400, ['message' => 'Invalid JSON']);
            }
        } elseif (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
            parse_str(file_get_contents('php://input'), $this->attributes);
        } elseif (strpos($contentType, 'multipart/form-data') !== false) {
            $this->attributes = $_POST;
        }

    }

    public function getRequestMethod() 
    {
        return $this->requestMethod;
    }

    private function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
    
    /**
     * getAttribute is used to get a specific attribute from the request
     * @generated Github CoPilot was used during the creation of this code
     */
    public function getAttribute($key) 
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : null;
    }

    /**
     * hasAttribute is used to check if a specific attribute exists in the request
     */
    public function hasAttribute($key) 
    {
        return isset($this->attributes[$key]);
    }

    /**
     * getAttributes is used to get all the attributes from the request
     */
    public function getAttributes() 
    {
        return $this->attributes;
    }
}
