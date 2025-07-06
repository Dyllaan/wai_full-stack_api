<?php

/**
 * InsertQuery is used to build an insert query in an object oriented way
 * @package App\Classes
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */

namespace App\Classes\Queries;

class InsertQuery extends \App\Classes\Query implements \App\Interfaces\QueryInterface
{
    private $cols;
    private $values = [];
    private $parameters = [];

    public function insert($table) 
    {
        $this->table = "INSERT INTO $table";
        return $this;
    }

    public function cols($cols) 
    {
        $this->cols = $cols;
        return $this;
    }

    public function values($values) 
    {
        foreach ($values as $key => $value) {
            $placeholder = ":value" . count($this->parameters);
            $this->parameters[$placeholder] = $value;
            $this->values[] = $placeholder;
        }
        return $this;
    }

    public function execute() 
    {
        if (empty($this->table) || empty($this->cols) || empty($this->values)) {
            throw new \App\ClientErrorException(422, ["message"=>"Incomplete query"]);
        }

        $valuesPart = implode(", ", $this->values);
        $query = "$this->table ($this->cols) VALUES ($valuesPart)";

        return $this->db->executeInsert($query, $this->parameters);
    }
}