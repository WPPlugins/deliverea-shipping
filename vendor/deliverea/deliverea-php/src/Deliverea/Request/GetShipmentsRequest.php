<?php

namespace Deliverea\Request;

class GetShipmentsRequest
{
    public function __construct(array $filters)
    {
        foreach ($filters as $filter => $value) {
            $this->$filter = $value;
        }
    }
}