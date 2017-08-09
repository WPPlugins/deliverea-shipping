<?php
namespace Deliverea\Model;

use Deliverea\Common\MagicGetAndSetTrait;
use Deliverea\Common\ToArrayTrait;

class Collection extends AbstractDeliverea
{
    use ToArrayTrait;

    use MagicGetAndSetTrait;

    /** @var string */
    public $shipping_dlvr_ref;

    /** @var \DateTime */
    public $collection_date;

    /** @var string */
    public $carrier_code;

    /** @var string */
    public $service_code;

    /** @var string */
    public $collection_client_ref;

    /** @var string */
    public $collection_dlvr_ref;

    /** @var string */
    public $collection_carrier_ref;

    /** @var string */
    public $hour_start_1;

    /** @var string */
    public $hour_end_1;

    /** @var string H:i */
    public $hour_start_2;

    /** @var string H:i */
    public $hour_end_2;

    /** @var double */
    public $cash_on_delivery;

    /** @var \DateTime */
    public $creation_date;

    /**
     * @param $collection_client_ref
     * @param $collection_date
     * @param $carrier_code
     * @param $service_code
     * @param $hour_start_1
     * @param $hour_end_1
     * @param $shipping_dlvr_ref
     */
    public function __construct(
        $collection_client_ref,
        $collection_date,
        $carrier_code,
        $service_code,
        $hour_start_1,
        $hour_end_1,
        $shipping_dlvr_ref
    )
    {
        $this->collection_client_ref = $collection_client_ref;
        $this->collection_date = $collection_date;
        $this->carrier_code = $carrier_code;
        $this->service_code = $service_code;
        $this->hour_start_1 = $hour_start_1;
        $this->hour_end_1 = $hour_end_1;
        $this->shipping_dlvr_ref = $shipping_dlvr_ref;
    }

    /**
     * @return mixed
     */
    public function getShippingDlvrRef()
    {
        return $this->shipping_dlvr_ref;
    }

    /**
     * @return \DateTime
     */
    public function getCollectionDate()
    {
        return $this->collection_date;
    }

    /**
     * @param \DateTime $collection_date
     */
    public function setCollectionDate($collection_date)
    {
        $this->collection_date = $collection_date;
    }

    /**
     * @return string
     */
    public function getCarrierCode()
    {
        return $this->carrier_code;
    }

    /**
     * @param string $carrier_code
     */
    public function setCarrierCode($carrier_code)
    {
        $this->carrier_code = $carrier_code;
    }

    /**
     * @return string
     */
    public function getServiceCode()
    {
        return $this->service_code;
    }

    /**
     * @param string $service_code
     */
    public function setServiceCode($service_code)
    {
        $this->service_code = $service_code;
    }

    /**
     * @return string
     */
    public function getCollectionClientRef()
    {
        return $this->collection_client_ref;
    }

    /**
     * @param string $collection_client_ref
     */
    public function setCollectionClientRef($collection_client_ref)
    {
        $this->collection_client_ref = $collection_client_ref;
    }

    /**
     * @return string
     */
    public function getCollectionDlvrRef()
    {
        return $this->collection_dlvr_ref;
    }

    /**
     * @param string $collection_dlvr_ref
     */
    public function setCollectionDlvrRef($collection_dlvr_ref)
    {
        $this->collection_dlvr_ref = $collection_dlvr_ref;
    }

    /**
     * @return string
     */
    public function getCollectionCarrierRef()
    {
        return $this->collection_carrier_ref;
    }

    /**
     * @param string $collection_carrier_ref
     */
    public function setCollectionCarrierRef($collection_carrier_ref)
    {
        $this->collection_carrier_ref = $collection_carrier_ref;
    }

    /**
     * @return string
     */
    public function getHourStart1()
    {
        return $this->hour_start_1;
    }

    /**
     * @param string $hour_start_1
     */
    public function setHourStart1($hour_start_1)
    {
        $this->hour_start_1 = $hour_start_1;
    }

    /**
     * @return string
     */
    public function getHourEnd1()
    {
        return $this->hour_end_1;
    }

    /**
     * @param string $hour_end_1
     */
    public function setHourEnd1($hour_end_1)
    {
        $this->hour_end_1 = $hour_end_1;
    }

    /**
     * @return string
     */
    public function getHourStart2()
    {
        return $this->hour_start_2;
    }

    /**
     * @param string $hour_start_2
     */
    public function setHourStart2($hour_start_2)
    {
        $this->hour_start_2 = $hour_start_2;
    }

    /**
     * @return string
     */
    public function getHourEnd2()
    {
        return $this->hour_end_2;
    }

    /**
     * @param string $hour_end_2
     */
    public function setHourEnd2($hour_end_2)
    {
        $this->hour_end_2 = $hour_end_2;
    }

    /**
     * @return mixed
     */
    public function getCashOnDelivery()
    {
        return $this->cash_on_delivery;
    }

    /**
     * @param mixed $cash_on_delivery
     */
    public function setCashOnDelivery($cash_on_delivery)
    {
        $this->cash_on_delivery = $cash_on_delivery;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @param \DateTime $creation_date
     */
    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    /**
     * @return array
     */
    public function getCustomCarrierParameters()
    {
        return $this->custom_carrier_parameters;
    }

    /**
     * @param $parameter
     * @param $value
     * @return $this
     */
    public function addCustomCarrierParameter($parameter, $value)
    {
        $this->custom_carrier_parameters[$parameter] = $value;
    }
}