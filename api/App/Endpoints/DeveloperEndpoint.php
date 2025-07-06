<?php
namespace App\Endpoints;
/**
 * DeveloperEndpoint is used to return the developer name and student id
 * 
 * @author Louis Figes W21O17657
 * @generated Github CoPilot was used during the creation of this code
 */

class DeveloperEndpoint extends \App\Classes\Endpoint\Endpoint 
{

    public function __construct() 
    {
        $this->addRequestMethod('GET');
    }

    public function process() 
    {
        $this->addResponse('name', 'Louis Figes');
        $this->addResponse('student_id', 'W21017657');
        $this->setResponseCode(200);
    }
}
