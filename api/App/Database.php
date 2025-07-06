<?php
/**
 * Database class, makes connection to the database and handles queries
 * @package App
 * @author Louis Figes
 * @generated This class was created using Github Copilot
 */
namespace App;

class Database 
{
	/**
	 * @var \PDO
	 */
    private $dbConnection;

	/**
	 * @var string
	 */
	private $dbName;
  
	/**
	 * Database constructor, initialises the database connection
	 * @param $dbName
	 */
    public function __construct($dbName) 
    {
		$this->dbName = $dbName;
        $this->setDbConnection($dbName);  
    }

 
	/**
	 * Opens a pdo connection or gives an error response
	 * @param $dbName
	 * @return void
	 * @throws \Exception
	 */
    private function setDbConnection($dbName) 
    {
        try { 
            $this->dbConnection = new \PDO('sqlite:'.$_SERVER['DOCUMENT_ROOT'] . "/api/db/" . $dbName);
            $this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch( \PDOException $e ) {
            $error['message'] = "Database Connection Error";
            $error['details'] = $e->getMessage();
            echo json_encode($error);
            exit(); 
        }
    }

	public function createSelect() 
	{
        return new \App\Classes\Queries\SelectQuery($this);
    }

	public function createInsert() 
	{
		return new \App\Classes\Queries\InsertQuery($this);
	}

	public function createUpdate() 
	{
		return new \App\Classes\Queries\UpdateQuery($this);
	}

	public function createDelete()
	{
		return new \App\Classes\Queries\DeleteQuery($this);
	}

    public function executeQuery($sql, $params = [])
	{
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

	public function executeInsert($sql, $params = [])
	{
		$stmt = $this->dbConnection->prepare($sql);
		$stmt->execute($params);
		return $this->dbConnection->lastInsertId();
    }
}