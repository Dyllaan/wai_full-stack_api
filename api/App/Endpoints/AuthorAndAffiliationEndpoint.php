<?php
/**
 * AuthorAndAffiliationEndpoint is used to get a list of authors and their affiliations
 * @package App\Endpoints
 * @generated Github CoPilot was used during the creation of this code
 * @author Louis Figes
 * Endpoint takes content and country parameters optionally and exclusively
 */
namespace App\Endpoints;

class AuthorAndAffiliationEndpoint extends \App\Classes\Endpoint\DBEndpoint {

    public function __construct() 
    {
        parent::__construct();
        $this->addRequestMethod('GET');
        $this->getGetAttributes()->addExclusiveString('country');
        $this->getGetAttributes()->addExclusiveInt('content');
        $this->getGetAttributes()->addAllowedString('search');
        $this->getGetAttributes()->addAllowedInt('page');
    }

    protected function processGET() 
    {
        $cols = "DISTINCT country, city, institution, author.name as author, content.id, content.title, affiliation.author as author_id";
        $conditions = [];

        if($this->getRequest()->hasAttribute('search') && $this->getRequest()->hasAttribute('content')) {
            throw new \App\ClientErrorException(400, ['message' => 'Cannot search by content and search at the same time']);
        } else if($this->getRequest()->hasAttribute('search') && ! $this->getRequest()->hasAttribute('content')) {
            $search = strtolower($this->getRequest()->getAttribute('search'));
            $conditions[] = "(LOWER(author.name) LIKE '%$search%' OR LOWER(content.title) LIKE '%$search%' OR LOWER(country) LIKE '%$search%' OR LOWER(city) LIKE '%$search%' OR LOWER(institution) LIKE '%$search%')";
        } else {
            if($this->getRequest()->hasAttribute('content')) {
                $content = $this->getRequest()->getAttribute('content');
                $content = strtolower($content);
                $conditions[] = "content.id = $content";
            }
        }

        if($this->getRequest()->hasAttribute('country')) {
            $country = $this->getRequest()->getAttribute('country');
            $country = strtolower($country);
            $conditions[] = "LOWER(country) = '$country'";
        }

        $select = $this->getDb()->createSelect()->select($cols)->from('affiliation')->where($conditions)->
        join("author", "author.id = affiliation.author")->
        join("content_has_author", "content_has_author.author = author.id")->
        join("content", "content.id = content_has_author.content");

        if($this->getRequest()->hasAttribute('page')) {
           $page = new \App\Util\Pagination($this->getRequest()->getAttribute('page'));
            $select->limit($page->getLimit())->offset($page->getOffset());
        }
        
        $this->setResponse($select->execute());
        $this->setResponseCode(200);
    }
}
