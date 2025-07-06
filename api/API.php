<?php
/**
 * Api is used to route api calls to the correct endpoint through the request handler
 * 
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
include_once 'config/autoloader.php';
include_once 'config/ExceptionHandler.php';

class API
{
    /**
     * @param $router - the request handler class
     */
    private $router;

    /**
    * when the api is called, register the error handler, parse and request the endpoint through the router
    */
    public function __construct()
    {
        ExceptionHandler::register();
        autoloaderRegister();
        $this->router = new App\Router($this->getRequestedEndpoint());
        $this->router->processEndpoint();
    }

    /**
     * Returns the string of the requested endpoint
     */
    private function getRequestedEndpoint() {
        $url = $_SERVER["REQUEST_URI"];
        $url = parse_url($url);
        $endpoint = trim($url['path'], '/');
        $endpoint = str_replace('api/', '', $endpoint);
        return $endpoint;
    }
}
