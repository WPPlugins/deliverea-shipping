<?php

namespace Deliverea\Response;

use Deliverea\Common\CreateShipmentTrait;
use Deliverea\Common\CreateTrackingEventTrait;
use Deliverea\Common\ToArrayTrait;
use Deliverea\Model\DetailedShipment;
use Deliverea\Model\TrackingEvents;

class GetShipmentTrackingResponse extends AbstractResponse
{
    protected $shipment;

    use ToArrayTrait;

    use CreateShipmentTrait;

    use CreateTrackingEventTrait;

    /**
     * @inheritdoc
     */
    function map($response)
    {
        $shipment = $this->createShipment($response);

        $events = [];
        foreach ($response->tracking_history as $event) {
            $events = $this->createTrackingEvent($event);
        }

        $trackingEvents = new TrackingEvents($this->createTrackingEvent($response), $events);

        $this->shipment = new DetailedShipment($shipment, null, null, $trackingEvents);

        return $this;
    }

    /**
     * @return DetailedShipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }
}