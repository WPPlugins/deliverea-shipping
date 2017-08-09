<?php

namespace Deliverea\Response;

use Deliverea\Common\ToArrayTrait;

class GetServiceInfoResponse extends AbstractResponse
{
    use ToArrayTrait;

    protected $available_methods = [];

    protected $schemas = [];

    protected $additional_information = [];

    /**
     * @return array
     */
    public function getAvailableMethods()
    {
        return $this->available_methods;
    }

    /**
     * @return array
     */
    public function getSchemas()
    {
        return $this->schemas;
    }

    /**
     * @return array
     */
    public function getAdditionalInformation()
    {
        return $this->additional_information;
    }
}