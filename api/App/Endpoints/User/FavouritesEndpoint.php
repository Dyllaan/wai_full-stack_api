<?php
/**
 * FavouritesEndpoint to handle CRUD operations on favourites
 * @package \App\Endpoints\User
 * @author Louis Figes
 * @generated This class was created using Github Copilot
 */
namespace App\Endpoints\User;

class FavouritesEndpoint extends \App\Classes\Endpoint\UserEndpoint 
{

    public function __construct() 
    {
        parent::__construct();
        $this->setRequestMethods(['GET', 'POST', 'DELETE']);
        $this->getGetAttributes()->addAllowedInt('content_id');
        $this->getPostAttributes()->addRequiredInt('content_id');
        $this->getDeleteAttributes()->addRequiredInt('content_id');
        $this->requiresAuthOn(['GET', 'POST', 'DELETE']);
    }

    private function getAllFavourites() 
    {
        $this->setResponse($this->getUser()->getFavourites());
        $this->setResponseCode(200);
    }

    public function processGET() 
    {
        if ($this->getRequest()->hasAttribute('content_id')) {
            $fav = new \App\Classes\Models\Favourite($this->getUserDb());
            $fav->setAccountId($this->getUser()->getId());
            $fav->setContentId($this->getRequest()->getAttribute('content_id'));
            if(!$fav->exists()) {
                $this->setResponse(["message" => "Content is not favourited"]);
                $this->setResponseCode(200);
            } else {
                $fav->get();
                $this->setResponse($fav->toArray());
                $this->setResponseCode(200);
            }
        } else {
            $this->setResponse($this->getUser()->getFavourites());
            $this->setResponseCode(200);
        }
    }

    public function processDELETE() 
    {
        $fav = new \App\Classes\Models\Favourite($this->getUserDb());
        $fav->setAccountId($this->getUser()->getId());
        $fav->setContentId($this->getRequest()->getAttribute('content_id'));
        $fav->delete();
        $this->setResponse(["message" => "Favourite deleted"]);
        $this->setResponseCode(200);
    }

    public function processPOST() 
    {
        $content = new \App\Classes\Models\Content($this->getDb());
        $content->setId($this->getRequest()->getAttribute('content_id'));
        if (!$content->exists()) {
            throw new \App\ClientErrorException(400, ['message' => "Content does not exist"]);
        }

        $favourite = new \App\Classes\Models\Favourite($this->getUserDb());
        $favourite->setAccountId($this->getUser()->getId());
        $favourite->setContentId($this->getRequest()->getAttribute('content_id'));
        $this->setResponse($favourite->save());
        $this->setResponseCode(201);
    }
}
