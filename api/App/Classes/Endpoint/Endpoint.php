<?php
/**
 * Endpoint is the class that all endpoints extend from
 * @package App\Classes\Endpoint
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Endpoint;

abstract class Endpoint 
{
    /**
     * @var $request - the request object
     */
    private $request;
    /**
     * @var $responses - the responses to be sent
     */
    private $responses = [];
    /**
     * @var $responseCode - the response code to be sent
     * default to 200
     */
    private $responseCode = 200;
    /**
     * @var $requestMethods - the request methods that the endpoint accepts
     */
    private $requestMethods = [];
    /**
     * @var $usedMethod - the request method that was used
     */
    private $usedMethod;

    public function respond() 
    {
        $response = new \App\Classes\HTTP\Response($this->getResponseCode(), $this->getResponses());
        $response->respond();
    }

    /**
     * Abstract method all endpoints implement, allows them to be called in the same manner
     */
    public abstract function process();

    public function setRequest($request) 
    {
        $this->request = $request;
    }

    public function getRequest() 
    {
        return $this->request;
    }

    public function getResponses() 
    {
        return $this->responses;
    }

    protected function addResponse($key, $response) 
    {
        $this->responses[$key] = $response;
    }

    protected function setResponse($response) 
    {
        $this->responses = $response;
    }

    protected function setResponseCode($responseCode) 
    {
        $this->responseCode = $responseCode;
    }

    protected function getResponseCode() 
    {
        return $this->responseCode;
    }

    public function getRequestMethods() 
    {
        return $this->requestMethods;
    }

    protected function setUsedMethod($usedMethod) 
    {
        $this->usedMethod = $usedMethod;
    }

    public function getUsedMethod() 
    {
        return $this->usedMethod;
    }

    protected function setRequestMethods($requestMethods) 
    {
        $this->requestMethods = $requestMethods;
    }

    protected function addRequestMethod($requestMethod) 
    {
        $this->requestMethods[] = $requestMethod;
    }
}