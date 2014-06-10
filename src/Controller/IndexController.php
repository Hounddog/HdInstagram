<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace HD\Instagram\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

class IndexController extends AbstractActionController
{
    /**
     * @var int From Zend\Json\Json
     */
    protected $jsonDecodeType = Json::TYPE_ARRAY;

    public function indexAction()
    {
        $method = strtolower($this->request->getMethod());

        if ($method == 'get') {
            return $this->get();
        } else {
            $content = $this->request->getContent();
            $answer =  Json::decode($content, $this->jsonDecodeType);
         
            $this->getEventManager()->trigger('update', $this, $answer);
        }

        return new ViewModel();
    }

    public function get()
    {
        $challenge = $this->request->getQuery()->get('hub_challenge', false);
        
        if ($challenge === false) {
            throw new \Exception('Missing challenge.');
        };
        $response = $this->getResponse();
        $response->setStatusCode(200);
        $response->setContent($challenge);
        return $response;
    }
}
