<?php
/**
 * EndpointAttributes class, is used to store the attributes of an endpoint for each method,
 * designed so that each endpoint can have different attributes for each method.
 * @author Louis Figes
 * @package App\Classes\Endpoint\Attributes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Endpoint\Attributes;

use \App\Interfaces\AttributeInterface;

class EndpointAttributes 
{
    private $exclusiveAttributes = [];
    private $requiredAttributes = [];
    private $allowed = [];
    private $method;
    
    public function __construct($method) 
    {
        $this->method = $method;
    }

    public function validate($request) 
    {
        $this->validateRequiredAttributes($request);
        $this->validateExclusiveAttributes($request);
        $this->validateAllowedAttributes($request);
    }


    /**
     * determines if the request attributes match the required attributes
     */
    protected function validateRequiredAttributes($requestAttributes) 
    {
        if($this->getRequiredAttributes() == null) {
            return;
        }

        if (!is_array($requestAttributes)) {
            throw new \App\ClientErrorException(400, ['message' => 'Request must be an array']);
        }

        $intersection = array_intersect_key($requestAttributes, array_flip(array_keys($this->getRequiredAttributes())));
        $missingAttributes = array_diff_key($this->getRequiredAttributes(), $intersection);
    
        if (count($missingAttributes) > 0) {
            throw new \App\ClientErrorException(400, ['missing' => array_keys($missingAttributes)]);
        }
    
        foreach ($intersection as $name => $value) {
            $attribute = $this->getAttributeByName($name);
            if (!$attribute->isValid($name, $value)) {
                throw new \App\ClientErrorException(400, ['invalid' => $name]);
            }
        }
    }
    

    /**
     * determines if the request attributes match the exclusive attributes
     */
    private function validateExclusiveAttributes($requestAttributes) 
    {
        if($this->getExclusiveAttributes() == null) {
            return;
        }
        if (!is_array($requestAttributes)) {
            throw new \App\ClientErrorException(400, ['message' => 'Request must be an array']);
        }

        $exclusiveNames = array_keys($this->getExclusiveAttributes());
        $intersection = array_intersect_key($requestAttributes, array_flip($exclusiveNames));

        if(count($intersection) > 1) 
        {
            throw new \App\ClientErrorException(400, ['exclusive' => array_keys($intersection)]);
        }

        foreach($intersection as $name => $value) 
        {
            $attribute = $this->getAttributeByName($name);
            if(!$attribute->isValid($name, $value)) 
            {
                throw new \App\ClientErrorException(400, ['invalid' => $name]);
            }
        }
    }

    /**
     * determines if the request attributes are allowed
     */
    private function validateAllowedAttributes($requestAttributes) 
    {
        $allowedAttributes = $this->getAll();

        if (empty($allowedAttributes)) {
            return;
        }

        if (!is_array($requestAttributes)) {
            throw new \App\ClientErrorException(400, ['message' => 'Request must be an array']);
        }

        foreach ($requestAttributes as $name => $value) {
            if (!isset($allowedAttributes[$name])) {
                throw new \App\ClientErrorException(400, ['disallowed' => $name]);
            }

            $attribute = $this->getAttributeByName($name);
            if (!$attribute->isValid($name, $value)) {
                throw new \App\ClientErrorException(400, ['invalid' => $name]);
            }
        }
    }

    public function getExclusiveAttributes() 
    {
        return $this->exclusiveAttributes;
    }

    public function addRequiredInt($name) 
    {
        $this->requiredAttributes[$name] = $this->createInt();
    }

    public function addRequiredInts($names) 
    {
        foreach($names as $name) {
            $this->requiredAttributes[$name] = $this->createInt();
        }
    }

    public function addRequiredString($name)
    {
        $this->requiredAttributes[$name] = $this->createString();
    }

    public function addRequiredStrings($names) 
    {
        foreach($names as $name) {
            $this->requiredAttributes[$name] = $this->createString();
        }
    }

    public function addAllowedInt($name) 
    {
        $this->allowed[$name] = $this->createInt();
    }

    public function addAllowedInts($names) 
    {
        foreach($names as $name) {
            $this->allowed[$name] = $this->createInt();
        }
    }

    public function addAllowedString($name) 
    {
        $this->allowed[$name] = $this->createString();
    }

    public function addAllowedStrings($names) 
    {
        foreach($names as $name) {
            $this->allowed[$name] = $this->createString();
        }
    }

    public function addExclusiveInt($name) 
    {
        $this->exclusiveAttributes[$name] = $this->createInt();
    }

    public function addExclusiveInts($names) 
    {
        foreach($names as $name) {
            $this->exclusiveAttributes[$name] = $this->createInt();
        }
    }

    public function addExclusiveString($name) 
    {
        $this->exclusiveAttributes[$name] = $this->createString();
    }

    public function addExclusiveStrings($names) 
    {
        foreach($names as $name) {
            $this->exclusiveAttributes[$name] = $this->createString();
        }
    }

    protected function createString() 
    {
        return new \App\Classes\Endpoint\Attributes\StringAttribute();
    }

    protected function createInt() 
    {
        return new \App\Classes\Endpoint\Attributes\IntAttribute();
    }

    protected function createInts($names) 
    {
        $attributes = [];
        foreach($names as $name) {
            $attributes[$name] = $this->createInt();
        }
        return $attributes;
    }

    protected function createStrings($names) 
    {
        $attributes = [];
        foreach($names as $name) {
            $attributes[$name] = $this->createString();
        }
        return $attributes;
    }

    public function getRequiredAttributes() 
    {
        return $this->requiredAttributes;
    }

    public function getMethod() 
    {
        return $this->method;
    }

    public function getAllowedAttributes() 
    {
        return $this->allowed;
    }
    
    public function isExclusiveAttribute($name) 
    {
        return isset($this->exclusiveAttributes[$name]);
    }

    public function isRequiredAttribute($name) 
    {
        return isset($this->requiredAttributes[$name]);
    }

    public function isAllowedAttribute($name) 
    {
        return isset($this->getAll()[$name]);
    }

    private function getAll() 
    {
        $merge = array_replace_recursive($this->exclusiveAttributes, $this->allowed);
        $merge = array_replace_recursive($merge, $this->requiredAttributes);
        return $merge;
    }

    private function getAttributeByName($name) 
    {
        return $this->getAll()[$name];
    }

    public function isThisMethod($method) 
    {
        return $this->method == $method;
    }

    public function setMethod($method) 
    {
        $this->method = $method;
    }
}