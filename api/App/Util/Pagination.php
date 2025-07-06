<?php
/**
 * Pagination
 * only used in content and author and affiliation endpoints
 * @package App\Util
 * @author Louis Figes
 * @generated Github CoPilot was used during the creation of this code
 */

namespace App\Util;

class Pagination
{
    private $page;
    private $limit;
    private $offset;

    public function __construct($page) 
    {
        $this->page = $page;
        $this->limit = 20;
        $this->offset = ($page - 1) * $this->limit;
    }

    private function handlePage()
    {
        if(!$this->getRequest()->hasAttribute('page')) {
            return $this->page;
        }

        $this->page = $this->getRequest()->getAttribute('page');

        if($this->page < 1) {
            $this->page = 1;
        } else {
            $this->page = intval($this->page);
        }
        return $page;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getOffset() {
        return $this->offset;
    }
}