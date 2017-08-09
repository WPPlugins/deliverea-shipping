<?php

namespace Deliverea\Response;

use Deliverea\Common\ToArrayTrait;

class NewCollectionResponse extends AbstractResponse
{
    /** @var string */
    protected $collection_dlvr_ref;

    /** @var string */
    protected $collection_client_ref;

    /** @var string */
    protected $collection_carrier_ref;

    /** @var string */
    protected $collection_carrier_guid;

    /** @var string */
    protected $carrier_code;

    /** @var string */
    protected $service_code;

    /** @var string */
    protected $carrier_phone;

    use ToArrayTrait;

    /**
     * @return string
     */
    public function getCollectionDlvrRef()
    {
        return $this->collection_dlvr_ref;
    }

    /**
     * @return string
     */
    public function getCollectionClientRef()
    {
        return $this->collection_client_ref;
    }

    /**
     * @return string
     */
    public function getCollectionCarrierRef()
    {
        return $this->collection_carrier_ref;
    }

    /**
     * @return string
     */
    public function getCollectionCarrierGuid()
    {
        return $this->collection_carrier_guid;
    }

    /**
     * @return string
     */
    public function getCarrierCode()
    {
        return $this->carrier_code;
    }

    /**
     * @return string
     */
    public function getServiceCode()
    {
        return $this->service_code;
    }

    /**
     * @return string
     */
    public function getCarrierPhone()
    {
        return $this->carrier_phone;
    }
}