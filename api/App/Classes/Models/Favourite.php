<?php
/**
 * Favourite is used to handle favourite data, including login and registration
 * Implements the CrudInterface to allow for easy database interaction
 * @package App\Classes\Models
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Models;

class Favourite extends \App\Classes\CrudModel implements \App\Interfaces\CrudInterface 
{
    private $accountId;
    private $contentId;
    private $created;

    public function get() 
    {
        if(!$this->exists()) {
            throw new \App\ClientErrorException(400, ['message' => "Content is not favourited"]);
        }
        $data = $this->getDb()->createSelect()->select("*")->from("favourites")->where(["content_id = '".$this->getContentId()."'", "account_id = '". $this->getAccountId() ."'"])->execute();
        if (count($data) == 0) {
            throw new \App\ClientErrorException(400, ['message' => "Favourite does not exist"]);
        } else {
            $this->setId($data[0]['favourite_id']);
            $this->setAccountId($data[0]['account_id']);
            $this->setContentId($data[0]['content_id']);
            $this->setCreated($data[0]['created']);
        }
    }

    public function save() 
    {
        if(!$this->checkSavable()) {
            throw new \App\ClientErrorException(400, ['message' => "Favourite is not savable"]);
        }
        if($this->exists()) {
            throw new \App\ClientErrorException(400, ['message' => "Favourite already exists"]);
        }

        $insert = $this->getDb()->createInsert()->insert("favourites")->cols("account_id, content_id")->values([$this->getAccountId(), $this->getContentId()])->execute();
        $this->get();
        return $this->toArray();
    }
    
    public function delete() 
    {
        if(!$this->checkSavable()) {
            throw new \App\ClientErrorException(400, ['message' => "Favourite is malformed"]);
        }
        if(!$this->exists()) {
            throw new \App\ClientErrorException(400, ['message' => "Favourite already exists"]);
        }

        $this->getDb()->createDelete()->from("favourites")->where(["account_id = '".$this->getAccountId()."'", "content_id = '".$this->getContentId()."'"])->execute();
    }

    public function update() 
    {
        throw new \App\ClientErrorException(400, ['message' => "Favourite is not updatable"]); 
    }

    public function exists() {
        if($this->getContentId() == null) {
            throw new \App\ClientErrorException(400, ['message' => "Missing id of content"]);
        }
        if($this->getAccountId() == null) {
            throw new \App\ClientErrorException(400, ['message' => "Missing id of account"]);
        }

        $data = $this->getDb()->createSelect()->select("*")->from("favourites")->where(["content_id = '".$this->getContentId()."'", "account_id = '".$this->getAccountId()."'"])->execute();
        return count($data) > 0;
    }

    /**
     * Checks if the favourite is savable or updatable
     * Requirements:
     * - Text must be longer than 0 characters
     * - Text must be shorter than 255 characters
     * - Content id must be set
     * - Account id must be set
     * - Content Id must be integer and greater than 0
     */
    private function checkSavable()
    {
        $errors = [];

        if (empty($this->getContentId())) {
            $errors[] = "Missing content id of favourite";
        } if (!is_numeric($this->getContentId())) {
            $errors[] = "Content id must be integer";
        } if ($this->getAccountId() == null) {
            $errors[] = "Missing account id of favourite";
        } if (!is_numeric($this->getAccountId())) {
            $errors[] = "Account id must be integer";
        } if ($this->getAccountId() <= 0) {
            $errors[] = "Account id must be greater than 0";
        } if ($this->getContentId() <= 0) {
            $errors[] = "Content id must be greater than 0";
        }

        if (!empty($errors)) {
            throw new \App\ClientErrorException(400, ['message' => $errors]);
        } 
        return true;
    }

    public function toArray() 
    {
        return [
            'favourite_id' => $this->getId(),
            'account_id' => $this->getAccountId(),
            'content_id' => $this->getContentId()
        ];
    }

    public function getAccountId() 
    {
        return $this->accountId;
    }

    public function setAccountId($accountId) 
    {
        $this->accountId = $accountId;
    }

    public function getContentId() 
    {
        return $this->contentId;
    }

    public function setContentId($contentId) 
    {
        $this->contentId = $contentId;
    }

    public function getCreated() 
    {
        return $this->created;
    }

    public function setCreated($created) 
    {
        $this->created = $created;
    }
}