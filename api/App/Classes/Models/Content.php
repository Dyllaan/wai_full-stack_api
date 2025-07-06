<?php
/**
 * Content is the class that represents the content table in the database
 * Implements the CrudInterface to allow for easy database interaction
 * @package App\Classes\Models
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Models;

class Content extends \App\Classes\CrudModel implements \App\Interfaces\CrudInterface 
{
    private $title;
    private $abstract;
    private $type;
    private $doiLink;
    private $award;
    private $authors;

    public function jsonify() 
    {
        return json_encode($this->toArray());
    }

    public function toArray() {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "abstract" => $this->getAbstract(),
            "type" => $this->getType(),
            "doi_link" => $this->getDoiLink(),
            "award" => $this->getAward(),
            "authors" => $this->getAuthors()
        ];
    }

    public function get() 
    {
        $data = $this->getDb()->createSelect()->select("*")->from("content")->where(["id = ".$this->getId()])->execute();
        if (count($data) == 0) {
            throw new \App\ClientErrorException(404, ['message' => "Content does not exist"]);
        } else {
            $this->setTitle($data[0]['title']);
            $this->setAbstract($data[0]['abstract']);
            $this->setType($data[0]['type']);
            $this->setDoiLink($data[0]['doi_link']);
            if(isset($data[0]['award'])) {
                $this->setAward($data[0]['award']);
            }
            $this->setAuthors($data[0]['authors']);
        } 
    }
    
    /**
     * save is not allowed for this class as the chi2023 db should not be modified
     */
    public function save() 
    {
        throw new \App\ClientErrorException(405, ['message' => "Method not allowed"]);
    }
    /**
     * update is not allowed for this class as the chi2023 db should not be modified
     */
    public function update() 
    {
        throw new \App\ClientErrorException(405, ['message' => "Method not allowed"]);
    }
    /**
     * delete is not allowed for this class as the chi2023 db should not be modified
     */
    public function delete() 
    {
        throw new \App\ClientErrorException(405, ['message' => "Method not allowed"]);
    }

    public function exists() 
    {
        if($this->checkForId()) {    
            $data = $this->getDb()->createSelect()->select("*")->from("content")->where(["id = ".$this->getId()])->execute();
            if (count($data) == 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * gets notes for a specific user
     */
    public function getNotes($uid) {
        if($this->checkForId()) {
            return $this->getDb()->createSelect()->attach("chi2023")->select("content_id, note_id, text, created")->
            from("notes")->where([" content_id = '".$this->getId()."'", "account_id = '" . $uid . "'"])->
            execute();
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Missing id of content"]);
        }
    }

    private function checkForId()
    {
        if($this->getId() == null) {
            throw new \App\ClientErrorException(400, ['message' => "Missing id of content"]);
        } else {
            return true;
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAbstract() 
    {
        return $this->abstract;
    }

    public function getType() 
    {
        return $this->type;
    }

    public function getDoiLink() 
    {
        return $this->doiLink;
    }

    public function getAward() 
    {
        return $this->award;
    }

    public function getAuthors() 
    {
        return $this->authors;
    }

    public function setTitle($title) 
    {
        $this->title = $title;
    }

    public function setAbstract($abstract)
     {
        $this->abstract = $abstract;
    }

    public function setType($type) 
    {
        $this->type = $type;
    }

    public function setDoiLink($doiLink) 
    {
        $this->doiLink = $doiLink;
    }

    public function setAward($award) 
    {
        $this->award = $award;
    }

    public function setAuthors($authors) 
    {
        $this->authors = $authors;
    }
}