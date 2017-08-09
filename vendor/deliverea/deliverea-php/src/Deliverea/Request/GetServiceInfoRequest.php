<?php

namespace Deliverea\Request;

class GetServiceInfoRequest
{
    /** @var string */
    public $carrier_code;

    /** @var string */
    public $service_code;

    /** @var string */
    public $from_country_code;

    /** @var string */
    public $from_zip_code;

    /** @var string */
    public $to_country_code;

    /** @var string */
    public $to_zip_code;

    /**
     * GetShipmentRequest constructor.
     * @param $carrierCode
     * @param $serviceCode
     * @param $fromCountryCode
     * @param $fromZipCode
     * @param $toCountryCode
     * @param $toZipCode
     */
    public function __construct($carrierCode, $serviceCode, $fromCountryCode, $fromZipCode, $toCountryCode, $toZipCode)
    {
        $this->carrier_code = $carrierCode;
        $this->service_code = $serviceCode;
        $this->from_country_code = $fromCountryCode;
        $this->from_zip_code = $fromZipCode;
        $this->to_country_code = $toCountryCode;
        $this->to_zip_code = $toZipCode;
    }
}