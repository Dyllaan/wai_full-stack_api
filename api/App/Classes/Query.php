<?php
/**
 * Query is the base class for all queries, just provides structure and some helper methods
 * @package App\Classes
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */

namespace App\Classes;

class Query 
{
    protected $db;
    protected $table;

    public function __construct($db) 
    {
        $this->db = $db;
    }

    public function attach($dbName) {
        if($dbName == "chi2023" || $dbName == "users") {
            $attachQuery = "ATTACH DATABASE '" . $_SERVER['DOCUMENT_ROOT'] . "/api/db/$dbName.sqlite' AS attachedDB;";
            $attachStmt = $this->db->executeQuery($attachQuery);
            return $this;
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Invalid database name"]);
        }
    }

    protected function conditionFormatter($conditions = []) 
    {
        if(!empty($conditions)) {
            $conditionString = "WHERE ";
            $conditionString .= implode(" AND ", $conditions);
            return $conditionString;
        } else {
            return "";
        }
    }
}