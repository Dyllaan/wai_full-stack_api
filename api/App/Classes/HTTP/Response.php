<?php
/**
 * Response is used to handle the response from the api, ensuring uniformity. 
 * It sets the headers and outputs the response
 * @package App\Classes\HTTP
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\HTTP;

class Response 
{
    /**
     * @var int
     */
    private $responseCode;
    
    /**
     * @var array
     */
    private $responses;

    public function __construct($responseCode, $responses) 
    {
        $this->responseCode = $responseCode;
        $this->responses = $responses;
    }

    /**
     * respond is used to set the headers and output the response
     * @return void
     */
    public function respond() 
    {
        \App\Classes\HTTP\Headers::setHeaders($this->responseCode);
        echo json_encode($this->responses);
        exit;
    }
}