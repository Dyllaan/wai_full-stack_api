<?php
/**
 * NotesEndpoint to handle CRUD operations on notes
 * @package \App\Endpoints\User
 * @author Louis Figes
 * @generated This class was created using Github Copilot
 */
namespace App\Endpoints\User;

class NotesEndpoint extends \App\Classes\Endpoint\UserEndpoint 
{

    public function __construct() 
    {
        parent::__construct();
        $methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $this->requiresAuthOn($methods);
        $this->setRequestMethods($methods);

        $this->getGetAttributes()->addExclusiveInts(['note_id', 'content_id']);

        $this->getPostAttributes()->addRequiredInt('content_id');
        $this->getPostAttributes()->addRequiredString('text');

        $this->getPutAttributes()->addRequiredInt('note_id');
        $this->getPutAttributes()->addRequiredString('text');
        
        $this->getDeleteAttributes()->addRequiredInt('note_id');
    }

    private function getContentNotes($id) 
    {
        $content = new \App\Classes\Models\Content($this->getUserDb());
        $content->setId($id);
        return $content->getNotes($this->getUser()->getId());
    }

    private function getNote($noteId) 
    {
        $note = new \App\Classes\Models\Note($this->getUserDb());
        $note->setId($noteId);
        return $note->get();
    }

    private function addNote($contentId, $text, $accountId) 
    {
        $note = new \App\Classes\Models\Note($this->getUserDb());
        $note->setText($text);
        $note->setContentId($contentId);
        $note->setAccountId($accountId);
        $note->save();
        return $note;
    }

    private function doesContentExist() 
    {
        $content = new \App\Classes\Models\Content($this->getDb());
        $content->setId($this->getRequest()->getAttribute('content_id'));
        
        if(!$content->exists()) {
            throw new \App\ClientErrorException(400, ['message' => "Content does not exist"]);
        } else {
            return true;
        }
    }

    private function editNote() {
        $note = new \App\Classes\Models\Note($this->getUserDb());
        $note->setId($this->getRequest()->getAttribute('note_id'));
        $note->get();
        $note->setAccountId($this->getUser()->getId());
        $note->setText($this->getRequest()->getAttribute('text'));
        $note->update();
        return $note->toArray();
    }

    private function deleteNote($noteId) {
        $note = new \App\Classes\Models\Note($this->getUserDb());
        $note->setId($noteId);
        $note->setAccountId($this->getUser()->getId());
        if($note->delete()){
            return ['message' => "Note deleted"];
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Note could not be deleted"]);
        }
    }

    public function processGET() 
    {
        if ($this->getRequest()->hasAttribute('content_id')) {
            $this->setResponse($this->getContentNotes($this->getRequest()->getAttribute('content_id')));
        } elseif ($this->getRequest()->hasAttribute('note_id')) {
            $this->setResponse($this->getNote($this->getRequest()->getAttribute('note_id')));

        } else {
            $this->setResponse($this->getUser()->getNotes());
        }
        $this->setResponseCode(200);
    }

    public function processPOST() 
    {
        if(!$this->doesContentExist()) {
            throw new \App\ClientErrorException(400, ['message' => "Content does not exist"]);
        }
        if($note = $this->addNote($this->getRequest()->getAttribute('content_id'), $this->getRequest()->getAttribute('text'), $this->getUser()->getId())) {
            $this->setResponse($note->toArray());
            $this->setResponseCode(201);
        } else {
            throw new \App\ClientErrorException(400, ['message' => "Note could not be saved"]);
        }
    }

    public function processPUT() 
    {
        $note = $this->editNote();
        $this->setResponse($note);
        $this->setResponseCode(201);
    }

    public function processDELETE() 
    {
        $this->setResponse($this->deleteNote($this->getRequest()->getAttribute('note_id')));
        $this->setResponseCode(200);
    }
}
