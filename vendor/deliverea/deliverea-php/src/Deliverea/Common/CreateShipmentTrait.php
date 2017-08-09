<?php

namespace Deliverea\Common;

use Deliverea\Model\Shipment;

trait CreateShipmentTrait
{
    /**
     * @param $data
     * @return Shipment
     */
    private function createShipment($data)
    {
        $details = $data;

        if (!empty($data->shipping_data)) {
            $details = array_merge((array)$data->shipping_data, (array)$data->parcel_data);
        }

        $shipment = new Shipment(
            $this->getValue($details, 'parcel_number', 1),
            $this->getValue($details, 'shipping_client_ref', ''),
            new \DateTime($this->getValue($details, 'shipping_date', '')),
            $this->getValue($details, 'service_type', ''),
            $this->getValue($details, 'carrier_code', ''),
            $this->getValue($details, 'service_code', '')
        );

        $shipment->setOriginalServiceCode($this->getValue($details, 'original_service_code', ''));
        $shipment->setShippingDlvrRef($this->getValue($details, 'shipping_dlvr_ref', ''));
        $shipment->setShippingCarrierRef($this->getValue($details, 'shipping_carrier_ref', ''));
        $shipment->setDocsNumber($this->getValue($details, 'docs_number', 0));
        $shipment->setCashOnDelivery($this->getValue($details, 'cash_on_delivery', 0));

        $shipment->setParcelWeight($this->getValue($details, 'parcel_weight', 0));
        $shipment->setParcelWidth($this->getValue($details, 'parcel_width', 0));
        $shipment->setParcelHeight($this->getValue($details, 'parcel_height', 0));
        $shipment->setParcelLength($this->getValue($details, 'parcel_length', 0));
        $shipment->setParcelVolume($this->getValue($details, 'parcel_volume', 0));
        $shipment->setReturnDlvrRef($this->getValue($details, 'return_dlvr_ref', ''));
        $shipment->setIsReturn($this->getValue($details, 'is_return', 0));

        $shipment->setCreationDate(new \DateTime($this->getValue($details, 'creation_date', '')));

        return $shipment;
    }
}