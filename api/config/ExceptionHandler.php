<?php

/**
 * ExceptionHandler is used to handle exceptions thrown by the api, uses code from the module
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */

abstract class ExceptionHandler
{

    /**
     * register is used to register the exception handler
     * @return void
     */
    public static function register()
    {
        set_exception_handler(array(__CLASS__, 'handleException'));
    }
 
    /**
     * handleException is used to handle exceptions thrown by the api for output in json
     * @generated Github CoPilot was used during the creation of this code
     * @return void
     */
    public static function handleException($e)
    {
        $output['exception'] = $e->getMessage();
        $output['file'] = $e->getFile();
        $output['line'] = $e->getLine();
    
        if ($e instanceof \App\ClientErrorException) {
            $code = $e->getResponseCode();
            if (!empty($e->getAdditionalData())) {
                $output = array_merge($output, $e->getAdditionalData());
            }
        } else {
            $code = 500;
        }
    
        App\Classes\HTTP\Headers::setHeaders($code);
        echo json_encode($output);
        exit();
    }
    
}