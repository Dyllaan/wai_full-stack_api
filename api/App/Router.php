<?php
/**
 * Router class, used to route requests to the correct endpoint
 * 
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App;

class Router 
{
    /**
     * @var array
     */
    private $endpoints = [];
    /**
     * @var string
     */
    private $endpoint;
    /**
     * @var string
     */
    private $endpointName;

    /**
     * middleware inspired by Laravel
     * @var array
     */
    private $middleware = [];

    public function __construct($endpointName) 
    {
        $this->populateMiddleware();
        $this->populateEndpoints();
        $this->setEndpointName($endpointName);
        $this->findEndpoint();
    }

    /**
     * populateMiddleware is used to populate the middleware array with the middleware
     * @return void
     */

    private function populateMiddleware()
    {
        $this->addMiddleware(new Middleware\SanitiseMiddleware());
    }

    /**
     * processEndpoint is used to process the endpoint, checking the request method, allocating the request and running the endpoint
     * Run from api.php
     */
    public function processEndpoint() 
    {
        $this->checkEndpointMethod();
        $this->allocateRequest();
        $this->runEndpoint();
    }

    /**
     * populateEndpoints is used to populate the endpoints array with the endpoints
     * @return void
     */
    private function populateEndpoints() 
    {
        $this->addEndpoint('developer', new Endpoints\DeveloperEndpoint());
        $this->addEndpoint('country', new Endpoints\CountryEndpoint());
        $this->addEndpoint('preview', new Endpoints\PreviewEndpoint());
        $this->addEndpoint('content', new Endpoints\ContentEndpoint());
        $this->addEndpoint('content-types', new Endpoints\ContentTypesEndpoint());
        $this->addEndpoint('favourites', new Endpoints\User\FavouritesEndpoint());
        $this->addEndpoint('current-user', new Endpoints\User\CurrentUserEndpoint());
        $this->addEndpoint('notes', new Endpoints\User\NotesEndpoint());
        $this->addEndpoint('author-and-affiliation', new Endpoints\AuthorAndAffiliationEndpoint());
        $this->addEndpoint('token', new Endpoints\User\TokenEndpoint());
    }

    /**
     * findEndpoint is used to find the endpoint in the endpoints array
     * @return void
     */
    private function findEndpoint() 
    {
        if ($this->endpointExists()) {
            $this->setEndpoint($this->endpointName);
        } else {
            $this->endpointNotFound($this->endpointName);
        }
    }

    /**
     * endpointExists is used to check if the endpoint exists in the endpoints array
     * @return bool
     */
    private function endpointExists() 
    {
        return array_key_exists($this->endpointName, $this->getEndpoints());
    }

    /**
     * setEndpoint is used to set the endpoint
     * @param $endpointName
     * @return void
     */
    private function setEndpoint($endpointName) 
    {
        if ($this->endpointExists($endpointName)) {
            $this->endpoint = $this->getEndpoints()[$endpointName];
        } else {
            $this->endpointNotFound($endpointName);
        }
    }

    /**
     * checkEndpointMethod is used to check if the request method is allowed for the endpoint
     * @return void
     * @throws \App\ClientErrorException
     */
    private function checkEndpointMethod() 
    {
        if ($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
            $response =  new \App\Classes\HTTP\Response(200, ["disallowed" => "Dont do that"]);
            $response->respond();
        } 
        if(!in_array($_SERVER['REQUEST_METHOD'], $this->getEndpoint()->getRequestMethods())) {
            throw new ClientErrorException(405, ['allowed' => $this->getEndpoint()->getRequestMethods()]);
        }
    }

    /**
     * allocateRequest is used to allocate the request to the endpoint
     * @return void
     */
    private function allocateRequest() 
    {
        $this->getEndpoint()->setRequest(new \App\Classes\HTTP\Request($_SERVER['REQUEST_METHOD']));
    }

    /**
     * endpointNotFound is used to throw a 404 error if the endpoint is not found
     * @param $endpointName
     * @return void
     * @throws \App\ClientErrorException
     */
    private function endpointNotFound($endpointName) 
    {
        throw new ClientErrorException(404, ['endpoint' => $endpointName]);
    }

    /**
     * 
     * @return void
     */
    private function runEndpoint() 
    {
        $this->getEndpoint()->process();
        $this->getEndpoint()->respond();
    }

    public function getEndpoint() 
    {
        return $this->endpoint;
    }

    public function getEndpoints() 
    {
        return $this->endpoints;
    }

    public function addEndpoint($key, $endpoint) 
    {
        $this->endpoints[$key] = $endpoint;
    }

    private function addMiddleware($middleware) 
    {
        $this->middleware[] = $middleware;
    }

    public function getMiddleware() 
    {
        return $this->middleware;
    }

    public function setEndpointName($endpointName) 
    {
        $this->endpointName = $endpointName;
    }

    public function getEndpointName() 
    {
        return $this->endpointName;
    }
}
