<?php
namespace Deliverea\Exception;

class CurlException extends \Exception
{
    private $endpoint;

    /**
     * @param string $endpoint
     */
    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function __toString()
    {
        return __CLASS__ . ' Could not communicate with Deliverea: ' . $this->endpoint;
    }
}