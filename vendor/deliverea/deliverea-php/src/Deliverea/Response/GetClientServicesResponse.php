<?php

namespace Deliverea\Response;

use Deliverea\Common\ToArrayTrait;
use Deliverea\Model\Service;
use Deliverea\Model\Carrier;

class GetClientServicesResponse extends AbstractResponse
{
    use ToArrayTrait;

    /** @var array */
    private $services = [];

    /**
     * @inheritdoc
     */
    function map($response)
    {
        foreach ($response as $item) {
            $carrier = new Carrier($item->carrier->carrier_name, $item->carrier->carrier_code);
            $this->services[] = new Service(
                $item->service_name,
                $item->service_code,
                $item->service_type, $carrier,
                $item->status);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        return $this->services;
    }
}