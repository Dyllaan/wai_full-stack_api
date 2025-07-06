<?php
/**
 * Headers is used to set the headers for the api
 * @package App\Classes\HTTP
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\HTTP;

class Headers 
{
    /**
     * setHeaders is used to set the headers for the api
     * @param $responseCode
     * @return void
     */
    public static function setHeaders($responseCode) 
    {
        header('HTTP/1.1 ' . $responseCode);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
}