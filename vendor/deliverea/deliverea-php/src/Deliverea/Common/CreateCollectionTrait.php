<?php

namespace Deliverea\Common;

use Deliverea\Model\Collection;

trait CreateCollectionTrait
{
    /**
     * @param $data
     * @return Collection
     */
    private function createCollection($data)
    {
        $details = $data->collection_data;

        $collection = new Collection(
            $this->getValue($details, 'collection_client_ref', ''),
            new \DateTime($this->getValue($details, 'collection_date', '')),
            $this->getValue($details, 'carrier_code', ''),
            $this->getValue($details, 'service_code', ''),
            $this->getValue($details, 'hour_start_1', ''),
            $this->getValue($details, 'hour_end_1', ''),
            ''
        );

        $collection->setCollectionDlvrRef($this->getValue($details, 'collection_dlvr_ref', ''));
        $collection->setCollectionCarrierRef($this->getValue($details, 'collection_carrier_ref', ''));

        $collection->setCreationDate(new \DateTime($this->getValue($details, 'creation_date', '')));

        return $collection;
    }
}