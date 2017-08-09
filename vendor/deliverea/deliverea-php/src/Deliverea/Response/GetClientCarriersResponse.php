<?php

namespace Deliverea\Response;

use Deliverea\Common\ToArrayTrait;
use Deliverea\Model\Carrier;

class GetClientCarriersResponse extends AbstractResponse
{
    use ToArrayTrait;

    /** @var array */
    private $carriers = [];

    /**
     * @inheritdoc
     */
    function map($response)
    {
        foreach ($response as $item) {
            $this->carriers[] = new Carrier($item->carrier_name, $item->carrier_code, $item->status);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getCarriers()
    {
        return $this->carriers;
    }
}