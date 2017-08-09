<?php
namespace Deliverea\Model;

use Deliverea\Common\ToArrayTrait;

class DetailedShipment extends AbstractDeliverea
{
    use ToArrayTrait;

    /** @var Shipment */
    private $shipment;

    /** @var Address */
    private $from;

    /** @var Address */
    private $to;

    /** @var TrackingEvents */
    private $tracking_events;

    /** @var SLAData */
    private $sla_data;

    /**
     * @param Shipment $shipment
     * @param Address $from
     * @param Address $to
     * @param TrackingEvents $tracking_events
     * @param SLAData $sla_data
     */
    public function __construct(
        Shipment $shipment,
        Address $from = null,
        Address $to = null,
        TrackingEvents $tracking_events = null,
        SLAData $sla_data = null
    ) {
        $this->shipment = $shipment;
        $this->from = $from;
        $this->to = $to;
        $this->tracking_events = $tracking_events;
        $this->sla_data = $sla_data;
    }

    /**
     * @return Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @param Shipment $shipment
     */
    public function setShipment($shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * @return Address
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param Address $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return Address
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param Address $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return TrackingEvents
     */
    public function getTrackingEvents()
    {
        return $this->tracking_events;
    }

    /**
     * @param TrackingEvents $tracking_events
     */
    public function setTrackingEvents($tracking_events)
    {
        $this->tracking_events = $tracking_events;
    }

    /**
     * @return SLAData
     */
    public function getSlaData()
    {
        return $this->sla_data;
    }

    /**
     * @param SLAData $sla_data
     */
    public function setSlaData($sla_data)
    {
        $this->sla_data = $sla_data;
    }
}