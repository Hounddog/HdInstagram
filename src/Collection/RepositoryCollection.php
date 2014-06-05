<?php

namespace HD\Instagram\Collection;

use HD\Api\Client\Http\Client;
use HD\Api\Client\Api\Model\Repo as RepoModel;
use Zend\Stdlib\Hydrator;

use Closure, Iterator;

class RepositoryCollection implements Iterator
{
    /**
     * @var client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $elements = array();

    protected $pagination = null;

    public function __construct(Client $httpClient, $path, array $parameters =array(), array $headers = array())
    {
            $this->httpClient = $httpClient;
            $this->path = $path;
            $this->headers = $headers;
            if(!isset($parameters['per_page'])) {
                $parameters['per_page'] = 20;;
            }

            if(!isset($parameters['page'])) {
                $parameters['page'] = 1;
            }
            $this->parameters = $parameters;
    }

    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    private function loadPage($page)
    {
        if($this->pagination != null && !property_exists($this->pagination, 'next_url')) {
            return false;
        }

        $elements = $this->fetch();

        if(count($elements) == 0) {
            return false;
        }

        $offset = (($page-1) * $this->parameters['per_page']);

        foreach($elements as $element) {
            $this->add($offset++, $element);
        }

        return true;
    }

    private function fetch()
    {
        $response = $this->httpClient->get($this->path, $this->parameters, $this->headers);

        $elements = json_decode($response->getBody());

        $this->getPagination($elements);
        $data = $elements->data;
        return $data;
    }

    public function page($page)
    {
        $this->parameters['per_page'] = $this->parameters['per_page'];
        $offsetStart = (($page-1) * $this->parameters['per_page']);
        $limit = $this->parameters['per_page'] -1;
        $elements = array();

        for($offset=$offsetStart,$i=0;$i<=$limit; $i++, $offset++){
            if(!$this->containsKey($offset)) {
                if($this->loadPage($page)) {
                    if($this->containsKey($offset)) {
                        $elements[] = $this->get($offset);
                    } else {
                        break;
                    }
                } else {
                    break;
                }
            } else  {
                $elements[] = $this->get($offset);
            }
        }

        return $elements;
    }

    public function add($offset, $element)
    {
        $this->elements[$offset] = $element;
    }


    private function getPagination($response)
    {
        $this->pagination  = $response->pagination;

        if(property_exists($this->pagination, 'next_url')) {
            $this->parameters['max_tag_id'] = $this->pagination->next_max_id;
        }
    }

    public function rewind()
    {
        reset($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->elements);
    }

    public function get($key)
    {
        return $this->elements[$key];
    }
    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->elements);
    }

    public function first()
    {
        $this->rewind();
        return $this->elements[$this->key()];
    }
    /**
     * {@inheritdoc}
     */
    public function next()
    {

        return next($this->elements);
    }

    public function prev()
    {
        return prev($this->elements);
    }

    public function getIterator()
    {
        $this->rewind();
        $this->parameters['page'] = 1;
        $this->parameters['per_page'] = 20;

        return $this;
    }

    public function valid()
    {
        if(!$this->current()) {
            $valid = $this->loadPage($this->parameters['page']);
            $this->parameters['page'] +=1;
            return $valid;
        }
        return true;
    }

    public function containsKey($key)
    {
        return array_key_exists($key, $this->elements);
    }

    public function count()
    {
        return count($this->elements);
    }

    public function indexOf($element)
    {
        return array_search($element, $this->elements);
    }

    public function removeElement($element)
    {
        $key = $this->indexOf($element);

        if($key) {
            unset($this->elements[$key]);
            return true;
        }
        return false;
    }
}