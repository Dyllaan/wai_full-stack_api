<?php
/**
 * Attribute class, used to validate integers
 * @author Louis Figes
 * @package App\Classes\Endpoint\Attributes
 * @generated Github Copilot was used in the creation of this code.
 */
namespace App\Classes\Endpoint\Attributes;

class IntAttribute implements \App\Interfaces\AttributeInterface
{

    public function isValid($name, $value) 
    {
        return \App\Util\Validator::validateInt($name, $value, 1);
    }
}