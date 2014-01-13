<?php

namespace HdInstagram\Api;

use HdApiClient\Api\AbstractApi;
use HdInstagram\Collection\RepositoryCollection;

class Subscribe extends AbstractApi
{
    /**
     * Subscribe to tag
     *
     * @link http://instagram.com/developer/realtime/#
     *
     * @param  string $tag
     * @param array $params
     * @return RepositoryCollection
     */
    public function tag($tag)
    {
        $config = $this->getServiceManager()->get('Config');
        $params['object'] = 'tag';
        $params['aspect'] = 'media';
        $params['object_id'] = $tag;
        $params['callback_url'] = $config['hdinstagram']['callback_url'];

        return $this->post('subscriptions', $params);
    }

    public function listing()
    {
        return $this->get('subscriptions');
    }
}