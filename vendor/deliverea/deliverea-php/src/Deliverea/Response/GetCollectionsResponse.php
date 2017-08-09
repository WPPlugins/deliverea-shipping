<?php

namespace Deliverea\Response;

use Deliverea\Common\CreateAddressTrait;
use Deliverea\Common\CreateCollectionTrait;
use Deliverea\Common\ToArrayTrait;
use Deliverea\Model\DetailedCollection;

class GetCollectionsResponse extends AbstractResponse
{
    protected $page = 1;

    protected $n_collections = 0;

    protected $collections = [];

    use ToArrayTrait;

    use CreateAddressTrait;

    use CreateCollectionTrait;

    /**
     * @inheritdoc
     */
    function map($response)
    {
        $this->page = $response->page;
        $this->n_collections = $response->n_collections;

        foreach ($response->collections as $item) {
            $collection = $this->createCollection($item);
            $from = $this->createAddress('from', $item->from_data);
            $to = $this->createAddress('to', $item->to_data);

            $this->collections[] = new DetailedCollection($collection, $from, $to);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getNCollections()
    {
        return $this->n_collections;
    }

    /**
     * @return array
     */
    public function getCollections()
    {
        return $this->collections;
    }
}