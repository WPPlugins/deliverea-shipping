<?php

namespace Deliverea\Response;

use Deliverea\Common\CreateAddressTrait;
use Deliverea\Common\ToArrayTrait;

class GetAddressesResponse extends AbstractResponse
{
    use ToArrayTrait;
    use CreateAddressTrait;

    /** @var array */
    private $addresses = [];

    /**
     * @inheritdoc
     */
    function map($response)
    {
        foreach ($response as $item) {
            $address = $this->createAddress('', $item);
            $address->setAddressId($item->address_id);
            $this->addresses[] = $address;

        }

        return $this;
    }
}