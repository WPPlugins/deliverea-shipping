<?php

namespace Deliverea\Request;

class GetClientServicesRequest
{
    /** @var string */
    public $carrier_code;

    /** @var string */
    public $service_code;

    /** @var string */
    public $service_region;

    /** @var string */
    public $service_type;

    /** @var int */
    public $status;

    public function __construct(
        $carrierCode = null,
        $serviceCode = null,
        $serviceRegion = null,
        $serviceType = null,
        $status = null
    ) {
        $this->carrier_code = $carrierCode;
        $this->service_code = $serviceCode;
        $this->service_region = $serviceRegion;
        $this->service_type = $serviceType;
        $this->status = $status;
    }
}