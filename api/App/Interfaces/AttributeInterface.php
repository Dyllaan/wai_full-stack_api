<?php
/**
 * Attribute interface, all attributes must have a validation function, this allows for other attributes to be created
 * @author Louis Figes
 * @package App\Interfaces
 * @generated Github Copilot was used in the creation of this code.
 */
namespace App\Interfaces;

interface AttributeInterface 
{
    public function isValid($name, $value);
}