<?php

namespace Deliverea\Response;

use Deliverea\Common\CreateAddressTrait;
use Deliverea\Common\CreateShipmentTrait;
use Deliverea\Common\ToArrayTrait;
use Deliverea\Model\DetailedShipment;
use Deliverea\Model\SLAData;

class GetShipmentsResponse extends AbstractResponse
{
    protected $page = 1;

    protected $n_shipments = 0;

    protected $shipments = [];

    use ToArrayTrait;

    use CreateAddressTrait;

    use CreateShipmentTrait;

    /**
     * @inheritdoc
     */
    function map($response)
    {
        $this->page = $response->page;
        $this->n_shipments = $response->n_shipments;

        foreach ($response->shipments as $item) {
            $shipment = $this->createShipment($item);
            $from = $this->createAddress('from', $item->from_data);
            $to = $this->createAddress('to', $item->to_data);

            $serviceLevelData = new SLAData(
                $item->sla_data->tracking_start_date,
                $item->sla_data->tracking_delivered_date,
                $item->sla_data->tracking_current_code,
                $item->sla_data->hours_elapsed
            );

            $this->shipments[] = new DetailedShipment($shipment, $from, $to, null, $serviceLevelData);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getNShipments()
    {
        return $this->n_shipments;
    }

    /**
     * @return array
     */
    public function getShipments()
    {
        return $this->shipments;
    }
}