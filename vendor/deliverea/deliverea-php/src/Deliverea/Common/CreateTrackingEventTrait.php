<?php

namespace Deliverea\Common;

use Deliverea\Model\TrackingEvent;

trait CreateTrackingEventTrait
{
    /**
     * @param $data
     * @return TrackingEvent
     */
    private function createTrackingEvent($data)
    {
        $trackingEvent = new TrackingEvent(
            $this->getValue($data, 'tracking_code', ''),
            $this->getValue($data, 'tracking_name', ''),
            $this->getValue($data, 'tracking_details', ''),
            new \DateTime($this->getValue($data, 'tracking_date', ''))
        );

        return $trackingEvent;
    }
}