<?php
/**
 * SelectQuery is used to build a select query in an object oriented way
 * @package App\Classes
 * @author Louis Figes W21017657
 * @generated Github CoPilot was used during the creation of this code
 */

namespace App\Classes\Queries;

class SelectQuery extends \App\Classes\Query implements \App\Interfaces\QueryInterface
{
    private $cols;
    private $where;
    private $joins;
    private $limit;
    private $offset;
    private $orderBy;

    public function select($cols) 
    {
        $this->cols = "SELECT $cols";
        return $this;
    }

    public function from($table) 
    {
        $this->table = "FROM $table";
        return $this;
    }

    public function where($conditions) 
    {
        $this->where = $this->conditionFormatter($conditions);
        return $this;
    }

    public function join($table, $on) 
    {
        $this->joins[] = "LEFT JOIN $table ON $on";
        return $this;
    }

    public function limit($limit) 
    {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function offset($offset) 
    {
        $this->offset = "OFFSET $offset";
        return $this;
    }

    public function orderBy($orderBy) 
    {
        $this->orderBy = "ORDER BY $orderBy";
        return $this;
    }

    /**
     * Assembles the query and executes it
     * does a check on joins or it throws an error that joins is not an array
     */
    public function execute() 
    {
        $parts = [
            $this->cols,
            $this->table,
            !empty($this->joins) ? implode(" ", $this->joins) : null,
            $this->where,
            $this->orderBy,
            $this->limit,
            $this->offset,
        ];

        $parts = array_filter($parts);

        $query = implode(" ", $parts);
        return $this->db->executeQuery($query);
    }
}