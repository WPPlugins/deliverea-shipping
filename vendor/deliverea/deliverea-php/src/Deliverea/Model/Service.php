<?php
namespace Deliverea\Model;

use Deliverea\Common\ToArrayTrait;

class Service extends AbstractDeliverea
{
    use ToArrayTrait;

    /** @var string */
    protected $service_name;

    /** @var string */
    protected $service_code;

    /** @var string */
    protected $service_type;

    /** @var Carrier */
    protected $carrier;

    /** @var int */
    protected $status;

    /**
     * Service constructor.
     * @param string $serviceName
     * @param string $serviceCode
     * @param string $serviceType
     * @param Carrier $carrier
     * @param int $status
     */
    public function __construct($serviceName, $serviceCode, $serviceType, Carrier $carrier, $status = null)
    {
        $this->service_name = $serviceName;
        $this->service_code = $serviceCode;
        $this->service_type = $serviceType;
        $this->carrier = $carrier;
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->service_name;
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
    public function getServiceType()
    {
        return $this->service_type;
    }

    /**
     * @return Carrier
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
}