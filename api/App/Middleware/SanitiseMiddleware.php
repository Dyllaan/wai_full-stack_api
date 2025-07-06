<?php
/**
 * The aim of this class it to sanitise any parameters or variables BEFORE the program uses them inspired by Laravel's middleware
 * I think this is the best solution as it avoids doing it in each endpoint and it seems more secure this way
 * @package App\Middleware
 * @author Louis Figes
 * @generated Github Copilot was used during the creation of this code
 */

namespace App\Middleware;

class SanitiseMiddleware
{
    /**
     * Sanitise all the request variables
     */
    public function __construct() 
    {
        $this->sanitiseRequest();
    }

    /**
     * Sanitise the request variables
     * even if they dont change, still sanitise them to be safe
     */
    private function sanitiseRequest()
    {
        if($this->isClean($_GET)) {
            $_GET = $this->sanitiseArray($_GET);
        }
        if($this->isClean($_POST)) {
            $_POST = $this->sanitiseArray($_POST);
        }
        if($this->isClean($_REQUEST)) {
            $_REQUEST = $this->sanitiseArray($_REQUEST);
        }
    }

    private function isClean($input)
    {
        if($this->sanitiseArray($input) !== $input) {
            throw new \App\ClientErrorException(400, ['message' => 'Invalid characters in request']);
        }
        return true;
    }

    /**
     * Sanitise a single variable
     */
    public function sanitise($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    /**
     * run the sanitise function on every element in the array
     */
    public function sanitiseArray($input)
    {
        $sanitisedArray = [];
        foreach ($input as $key => $value) {
            $sanitisedArray[$key] = $this->sanitise($value);
        }
        return $sanitisedArray;
    }
}