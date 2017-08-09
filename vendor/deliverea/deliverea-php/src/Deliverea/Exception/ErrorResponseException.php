<?php
namespace Deliverea\Exception;

class ErrorResponseException extends \Exception
{
    private $errorCode;

    private $errorMessage;

    /**
     * @param string $errorCode
     * @param string $errorMessage
     */
    public function __construct($errorCode, $errorMessage)
    {
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function __toString()
    {
        return  __CLASS__ . ' Deliverea responded with an error, errorCode: ' . $this->errorCode . ' - ' . $this->errorMessage;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}