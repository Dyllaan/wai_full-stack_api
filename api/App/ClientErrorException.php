<?php
/**
 * ClientErrorException is used to handle exceptions thrown by the api, uses code from the module
 * @package App
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App;
class ClientErrorException extends \Exception
{
    /**
     * @var array
     */
    protected $additionalData;

    /**
     * @var int
     */
    protected $responseCode;

    /**
     * ClientErrorException constructor.
     * @param $code
     * @param array $additionalData
     * @return void
     */
    public function __construct($code, $additionalData = [])
    {
        parent::__construct($this->errorResponses($code));
        $this->setResponseCode($code);
        $this->additionalData = $additionalData;
    }

    /**
     * errorResponses is used to return the correct error message for the given error code
     * @param $code
     * @return string
     */
 
    public function errorResponses($code)
    {
        $message = '';
        switch ($code) {
            case 404:
                $message = 'Not Found';
                break;
            case 405:
                $message = 'Method Not Allowed';
                break;
            case 422:
                $message = 'Unprocessable Entity';
                break;
            case 400:
                $message = 'Bad Request';
                break;
            case 401:
                $message = 'Unauthorized';
                break;
            default:
                $message = 'Internal Server Error';
        }
        return $message;
    }

    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }

    public function setResponseCode($code)
    {
        $this->responseCode = $code;
    }
}