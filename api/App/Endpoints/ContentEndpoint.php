<?php
namespace App\Endpoints;
/**
 * Allows the API user to query the content table
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 * @package App\Endpoints
 */
class ContentEndpoint extends \App\Classes\Endpoint\DBEndpoint 
{

    private $defaultPage = 1;
    private $defaultLimit = 20;

    public function __construct() 
    {
        parent::__construct();
        $this->addRequestMethod('GET');
        $this->getGetAttributes()->addAllowedInts(['page', 'id']);
        $this->getGetAttributes()->addAllowedStrings(['type', 'award', 'search', 'country', 'count']);
    }

    private function handleType() 
    {
        if($this->getRequest()->hasAttribute('type')){
            $type = $this->getRequest()->getAttribute('type');
            $type = strtolower($type);
            if($type == "all") {
                return [];
            } else {
                return "LOWER(t.name) = '$type'";
            }
        } else {
            return [];
        }
    }

    private function handleAward() 
    {
        if(!$this->getRequest()->hasAttribute('award')) {
            return [];
        }
        $condition = "";
        if($this->getRequest()->hasAttribute('award')) {
            $award = $this->getRequest()->getAttribute('award');
            if(strtolower($award) == "true") {
                $condition = "cha.award IS NOT NULL";
            } else {
                $condition = "cha.award IS NULL";
            }

        }
        return $condition;
    }

    private function handleSearch() 
    {
        if(!$this->getRequest()->hasAttribute('search')) {
            return [];
        }
        $search = $this->getRequest()->getAttribute('search');
        $search = strtolower($search);
        $newCondition = "(LOWER(c.title) LIKE '%". $search ."%'";
        $newCondition .= " OR LOWER(c.abstract) LIKE '%". $search ."%'";
        $newCondition .= " OR LOWER(c.doi_link) LIKE '%". $search ."%')";
        return $newCondition;
    }

    private function handleId() 
    {
        if($this->getRequest()->hasAttribute('id')){
            $cId = $this->getRequest()->getAttribute('id');
            $content = new \App\Classes\Models\Content($this->getDb());
            $content->setId($cId);
            if(!$content->exists()) {
                throw new \App\ClientErrorException(404, ['message' => 'Content not found']);
            } 
            $id = $this->getRequest()->getAttribute('id');
            $id = intval($id);
            return "c.id = $id";
        }
    }

    private function handleCountry() 
    {
        if(!$this->getRequest()->hasAttribute('country')) {
            return [];
        }
        $country = $this->getRequest()->getAttribute('country');
        $country = strtolower($country);
        return "LOWER(aff.country) = '$country'";
    }

    public function handleCount() 
    {
        if(!$this->getRequest()->hasAttribute('count')) {
            return [];
        }
        $count = $this->getRequest()->getAttribute('count');
        $count = strtolower($count);
        if($count == "true") {
            $select = $this->getDb()->createSelect()->select("COUNT(*) as count")->
            from("content c")->
            execute();
            $this->setResponse($select);
            $this->setResponseCode(200);
        }
    }

    public function buildConditions() 
    {
        $conditions = [];
        $conditions[] = $this->handleType();
        $conditions[] = $this->handleAward();
        $conditions[] = $this->handleSearch();
        $conditions[] = $this->handleId();
        
        return array_filter($conditions);
    }
    

    public function processGET() 
    {
        if($this->getRequest()->hasAttribute('count')) {
            $this->handleCount();
            return;
        }
        $page = new \App\Util\Pagination($this->getRequest()->getAttribute('page'));
        $limit = $page->getLimit();
        $offset = $page->getOffset();
        $select = $this->getDb()->createSelect()->select("c.id, c.title, c.abstract, t.name as type, c.doi_link, cha.award, preview_video")->
        from("content c")->
        join("content_has_award cha", "c.id = cha.content")->
        join("type t", "c.type = t.id")->
        where($this->buildConditions())->
        limit($limit)->
        offset($offset)->execute();

        $this->setResponse($select);
        $this->setResponseCode(200);
    }
}
