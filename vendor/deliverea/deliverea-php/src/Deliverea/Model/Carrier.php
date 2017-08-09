<?php
namespace Deliverea\Model;

use Deliverea\Common\ToArrayTrait;

class Carrier extends AbstractDeliverea
{
    use ToArrayTrait;

    /** @var string */
    protected $carrier_name;

    /** @var string */
    protected $carrier_code;

    /** @var int */
    protected $status;

    /**
     * Carrier constructor.
     * @param string $carrierName
     * @param string $carrierCode
     * @param int $status
     */
    public function __construct($carrierName, $carrierCode, $status = null)
    {
        $this->carrier_name = $carrierName;
        $this->carrier_code = $carrierCode;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getCarrierName()
    {
        return $this->carrier_name;
    }

    /**
     * @return mixed
     */
    public function getCarrierCode()
    {
        return $this->carrier_code;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}