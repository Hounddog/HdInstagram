<?php

namespace HD\Instagram\Listener\Auth;

use Zend\EventManager\Event;
use Zend\Validator\NotEmpty;
use HD\Api\Client\Listener\Auth\AbstractAuthListener;

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

        if (!array_key_exists('client_id', $this->options)
            || !$validator->isValid($this->options['client_id'])
        ) {
            throw new Exception\InvalidArgumentException('You need to set client_id!');
        }

        $request = $e->getTarget();

        $query = $request->getQuery();
        $query->set('client_id', $this->options['client_id']);
    }
}
