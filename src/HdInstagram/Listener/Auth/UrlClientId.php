<?php

namespace HdInstagram\Listener\Auth;

use Zend\EventManager\Event;
use Zend\Validator\NotEmpty;
use HdApiClient\Listener\Auth\AbstractAuthListener;

class UrlClientId extends AbstractAuthListener
{
    /**
     * Add Client Id and Client Secret to Request Parameters
     *
     * @throws Exception\InvalidArgumentException
     */
    public function preSend(Event $e)
    {
        $validator = new NotEmpty();

        if (
            !isset($this->options['tokenOrLogin'])
            || !$validator->isValid($this->options['tokenOrLogin'])
        ) {
            throw new Exception\InvalidArgumentException('You need to set client_id!');
        }

        $request = $e->getTarget();

        $query = $request->getQuery();
        $query->set('client_id', $this->options['tokenOrLogin']);
    }
}
