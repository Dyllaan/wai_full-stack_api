<?php
/**
 * DeleteQuery is used to build a delete query in an object oriented way
 * @package App\Classes
 * @author Louis Figes 
 * @generated Github CoPilot was used during the creation of this code
 */

namespace App\Classes\Queries;

class DeleteQuery extends \App\Classes\Query implements \App\Interfaces\QueryInterface
{
    private $where;

    public function from($table) 
    {
        $this->table = "DELETE FROM $table";
        return $this;
    }

    public function where($conditions) 
    {
        $this->where = $this->conditionFormatter($conditions);
        return $this;
    }

    public function execute() 
    {
        if(empty($this->table)) {
            throw new \App\ClientErrorException(422, ["message"=>"Incomplete query"]);
        }
        $query = "$this->table $this->where";
        return $this->db->executeQuery($query);
    }
}