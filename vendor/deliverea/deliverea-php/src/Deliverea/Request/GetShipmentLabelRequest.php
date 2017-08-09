<?php

namespace Deliverea\Request;

class GetShipmentLabelRequest
{
    /** @var string */
    public $dlvr_ref;

    /**
     * @param $dlvr_ref
     */
    public function __construct($dlvr_ref)
    {
        $this->dlvr_ref = $dlvr_ref;
    }
}