<?php

namespace Deliverea\Request;

class GetCollectionsRequest
{

    public function __construct(array $filters)
    {
        foreach ($filters as $filter => $value) {
            $this->$filter = $value;
        }
    }
}