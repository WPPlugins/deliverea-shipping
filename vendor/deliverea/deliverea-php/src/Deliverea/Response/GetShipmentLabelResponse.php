<?php

namespace Deliverea\Response;

use Deliverea\Common\ToArrayTrait;

class GetShipmentLabelResponse extends AbstractResponse
{
    use ToArrayTrait;

    /** @var string */
    protected $shipping_dlvr_ref;

    /** @var string */
    protected $shipping_client_ref;

    /** @var string */
    protected $shipping_carrier_ref;

    /** @var string */
    protected $shipping_carrier_guid;

    /** @var string */
    protected $label_raw;

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
    public function getLabelRaw()
    {
        return $this->label_raw;
    }
}