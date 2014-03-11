<?php

namespace HD\Instagram\Api;

use HdApiClient\Api\AbstractApi;
use HD\Instagram\Collection\RepositoryCollection;

class Media extends AbstractApi
{
    /**
     * Get Recent Media
     *
     * @link http://instagram.com/developer/endpoints/tags/#get_tags_media_recent
     *
     * @param  string $tag
     * @param array $params
     * @return RepositoryCollection
     */
    public function fetch($id)
    {
        return $this->get('media/' . $id);
    }

    public function popular()
    {
        # code...
    }

    public function search()
    {
        # code...
    }
}