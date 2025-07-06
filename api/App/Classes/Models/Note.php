<?php
/**
 * Note is used to handle note data, including login and registration
 * Implements the CrudInterface to allow for easy database interaction
 * @package App\Classes\Models
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */
namespace App\Classes\Models;

class Note extends \App\Classes\CrudModel implements \App\Interfaces\CrudInterface 
{
    private $accountId;
    private $contentId;
    private $text;
    private $created;

    /**
     * Checks if the note is savable or updatable
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
        if ($this->getText() == null) {
            $errors[] = "Missing text of note";
        } elseif (strlen($this->getText()) == 0) {
            $errors[] = "Text of note must be longer than 0 characters";
        } elseif (strlen($this->getText()) > 255) {
            $errors[] = "Text of note must be shorter than 255 characters";
        } if (empty($this->getContentId())) {
            $errors[] = "Missing content id of note";
        } else if (!is_numeric($this->getContentId())) {
            $errors[] = "Content id must be integer";
        } if ($this->getAccountId() == null) {
            $errors[] = "Missing account id of note";
        } else if (!is_numeric($this->getAccountId())) {
            $errors[] = "Account id must be integer";
        } else if ($this->getAccountId() <= 0) {
            $errors[] = "Account id must be greater than 0";
        } else if ($this->getContentId() <= 0) {
            $errors[] = "Content id must be greater than 0";
        }

        if (!empty($errors)) {
            throw new \App\ClientErrorException(400, ['message' => $errors]);
            return false;
        } else {
            return true;
        }
    }

    public function get() 
    {
        if($this->exists()) {
            $data = $this->getDb()->createSelect()->select("*")->from("notes")->where([" note_id = '".$this->getId()."'"])->execute();
            $this->setAccountId($data[0]['account_id']);
            $this->setContentId($data[0]['content_id']);
            $this->setText($data[0]['text']);
            $this->setCreated($data[0]['created']);
            return $this->toArray();
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Note does not exist"]);
        } 
    }

    public function save() 
    {
        if(!$this->checkSavable()) {
            throw new \App\ClientErrorException(400, ['message' => "Note is not savable"]);
        }
        $id = $this->getDb()->createInsert()->insert('notes')->cols("content_id, text, account_id")->values([$this->getContentId(), $this->getText(), $this->getAccountId()])->execute();
        if($id != null) {
            $this->setId($id);
            return $this->toArray();
        }
        return false;
    }

    public function update() 
    {
        if($this->hasPermission() && $this->checkSavable()) {
            $this->getDb()
            ->createUpdate()
            ->update('notes')
            ->set(['text' => $this->getText()])
            ->where(["note_id = '".$this->getId()."'"])
            ->execute();
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Note is not updatable"]);
        }
    }

    public function delete() 
    {
        if($this->hasPermission()){
            $this->getDb()->createDelete()
            ->from('notes')
            ->where(["note_id = '".$this->getId()."'"])
            ->execute();
            return true;
        } else {
            throw new \App\ClientErrorException(400, ['message' => "You do not have permission"]);
        }
    }

    public function hasPermission() {
        if($this->exists()) {
            $data = $this->getDb()->createSelect()->select("*")->from("notes")->where(["note_id = '".$this->getId()."'"])->execute();
            if($data[0]['account_id'] == $this->getAccountId()) {
                return true;
            } else {
                throw new \App\ClientErrorException(400, ['message' => "You do not have permission"]);
            }
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Note does not exist"]);
        }
    }

    public function toArray() 
    {
        return [
            'note_id' => $this->getId(),
            'account_id' => $this->getAccountId(),
            'content_id' => $this->getContentId(),
            'text' => $this->getText()
        ];
    }

    public function exists() {
        if($this->getId() == null) {
            throw new \App\ClientErrorException(400, ['message' => "Missing id of note"]);
        }
        $data = $this->getDb()->createSelect()->select("*")->from("notes")->where(["note_id = '".$this->getId()."'"])->execute();
        if (count($data) == 0) {
            throw new \App\ClientErrorException(400, ['message' => "Note does not exist"]);
        } else {
            return true;
        }
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

    public function getText() 
    {
        return $this->text;
    }

    public function setText($text) 
    {
        $this->text = $text;
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