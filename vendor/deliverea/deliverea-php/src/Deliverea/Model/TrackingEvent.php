<?php
namespace Deliverea\Model;

use Deliverea\Common\ToArrayTrait;

class TrackingEvent extends AbstractDeliverea
{
    use ToArrayTrait;

    /** @var string */
    private $tracking_code;

    /** @var string */
    private $tracking_name;

    /** @var string */
    private $tracking_details;

    /** @var \DateTime */
    private $tracking_date;

    public function __construct(
        $tracking_code,
        $tracking_name,
        $tracking_details,
        \DateTime $tracking_date
    ) {
        $this->tracking_code = $tracking_code;
        $this->tracking_name = $tracking_name;
        $this->tracking_details = $tracking_details;
        $this->tracking_date = $tracking_date;
    }

    /**
     * @return string
     */
    public function getTrackingCode()
    {
        return $this->tracking_code;
    }

    /**
     * @param string $tracking_code
     */
    public function setTrackingCode($tracking_code)
    {
        $this->tracking_code = $tracking_code;
    }

    /**
     * @return string
     */
    public function getTrackingName()
    {
        return $this->tracking_name;
    }

    /**
     * @param string $tracking_name
     */
    public function setTrackingName($tracking_name)
    {
        $this->tracking_name = $tracking_name;
    }

    /**
     * @return string
     */
    public function getTrackingDetails()
    {
        return $this->tracking_details;
    }

    /**
     * @param string $tracking_details
     */
    public function setTrackingDetails($tracking_details)
    {
        $this->tracking_details = $tracking_details;
    }

    /**
     * @return \DateTime
     */
    public function getTrackingDate()
    {
        return $this->tracking_date;
    }

    /**
     * @param \DateTime $tracking_date
     */
    public function setTrackingDate($tracking_date)
    {
        $this->tracking_date = $tracking_date;
    }
}