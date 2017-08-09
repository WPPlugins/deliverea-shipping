<?php

namespace Deliverea\Response;

use Deliverea\Common\ToArrayTrait;

class NewShipmentResponse extends AbstractResponse
{
    /** @var string */
    protected $shipping_dlvr_ref;

    /** @var string */
    protected $shipping_client_ref;

    /** @var string */
    protected $shipping_carrier_ref;

    /** @var string */
    protected $shipping_carrier_guid;

    /** @var string */
    protected $service_type;

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
    public function getShippingDlvrRef()
    {
        return $this->shipping_dlvr_ref;
    }

    /**
     * @return string
     */
    public function getShippingClientRef()
    {
        return $this->shipping_client_ref;
    }

    /**
     * @return string
     */
    public function getShippingCarrierRef()
    {
        return $this->shipping_carrier_ref;
    }

    /**
     * @return string
     */
    public function getShippingCarrierGuid()
    {
        return $this->shipping_carrier_guid;
    }

    /**
     * @return string
     */
    public function getServiceType()
    {
        return $this->service_type;
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