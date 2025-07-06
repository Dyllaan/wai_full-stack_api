<?php
/**
 * Crud Model gives the common functions
 * @package App\Classes
 * @auther Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */

namespace App\Classes;

class CrudModel {

    private $db;
    private $id;
    
    public function __construct($db) 
    {
        $this->db = $db;
    }

    public function getId() 
    {
        return $this->id;
    }

    public function setId($id) 
    {
        $this->id = $id;
    }

    protected function getDb() 
    {
        return $this->db;
    }

    private function setDb($db) 
    {
        $this->db = $db;
    }
}