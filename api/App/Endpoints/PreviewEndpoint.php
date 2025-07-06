<?php
/**
 * Returns the content items with a preview video in random order
 * @package App\Endpoints
 * @author Louis Figes
 */
namespace App\Endpoints;

class PreviewEndpoint extends \App\Classes\Endpoint\DBEndpoint {

    public function __construct() 
    {
        parent::__construct();
        $this->addRequestMethod('GET');
        $this->getGetAttributes()->addAllowedInt('limit');
    }

    /**
     * selects iotems with a preview video from the content table in random order
     * allows the limit param
     */
    public function processGET()
    {
        
        $select = $this->getDb()->createSelect()
        ->select('id, title, preview_video')
        ->from('content')
        ->where(['preview_video IS NOT NULL'])
        ->orderBy('RANDOM()');

        if ($this->getRequest()->hasAttribute('limit')) {
            $limit = $this->getRequest()->getAttribute('limit');
            $select->limit($limit);
        }

        $this->setResponse($select->execute());
        $this->setResponseCode(200);
    }
}
