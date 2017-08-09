<?php
namespace Deliverea\Model;

use Deliverea\Common\ToArrayTrait;

class SLAData extends AbstractDeliverea
{
    use ToArrayTrait;

    /** @var string */
    private $tracking_start_date;

    /** @var string */
    private $tracking_delivered_date;

    /** @var string */
    private $tracking_current_code;

    /** @var int */
    private $hours_elapsed;

    public function __construct(
        $tracking_start_date = null,
        $tracking_delivered_date = null,
        $tracking_current_code = null,
        $hours_elapsed = 0
    ) {
        $this->tracking_start_date = $tracking_start_date;
        $this->tracking_delivered_date = $tracking_delivered_date;
        $this->tracking_current_code = $tracking_current_code;
        $this->hours_elapsed = $hours_elapsed;
    }

    /**
     * @return string
     */
    public function getTrackingStartDate()
    {
        return $this->tracking_start_date;
    }

    /**
     * @return string
     */
    public function getTrackingDeliveredDate()
    {
        return $this->$tracking_delivered_date;
    }

    /**
     * @return string
     */
    public function getTrackingCurrentCode()
    {
        return $this->tracking_current_code;
    }

    /**
     * @return int
     */
    public function getHoursElapsed()
    {
        return $this->hours_elapsed;
    }


}