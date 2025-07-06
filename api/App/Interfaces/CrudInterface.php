<?php
/**
 * Defines rules for each of the objects that the API uses, like Content
 * @package App\Interfaces
 * @author Louis Figes
 */
namespace App\Interfaces;

interface CrudInterface 
{
    public function get();
    public function save();
    public function update();
    public function delete();
    public function toArray();
    public function exists();
}