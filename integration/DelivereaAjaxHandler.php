<?php

/**
 * Class DelivereaAjaxHandler
 */
class DelivereaAjaxHandler
{
    /**
     * @return \Deliverea\Deliverea
     */
    public function getDelivereaClient()
    {
        $user = (string)get_option('wcdeliverea_api_user', '');
        $password = (string)get_option('wcdeliverea_api_key', '');

        $class = '\Deliverea\Deliverea';

        return new $class($user, $password);
    }

    /**
     * @param $shippingDlvrRef
     *
     * @return array|\Deliverea\Response\GetShipmentLabelResponse
     */
    public function generateLabel($shippingDlvrRef)
    {
        $label = $this->getShipmentLabel($shippingDlvrRef);

        return [
            'type' => 'ok',
            // TODO Place in method
            'data' => [
                'shipping_dlvr_ref' => $shippingDlvrRef,
                'label' => $label->getLabelRaw(),
            ],
        ];
    }

    /**
     * @param array $data
     * @param \Closure $callback
     * @return \Deliverea\Response\NewShipmentResponse|string
     */
    public function newShipment($data, $callback = null)
    {
        $delivereaClient = $this->getDelivereaClient();

        $class = '\Deliverea\Model\Shipment';

        /** @var \Deliverea\Model\Shipment $shipment */
        $shipment = new $class(
            $data['parcel_number'],
            $data['shipping_client_ref'],
            $data['shipping_date'],
            $data['send_type'],
            $data['carrier_code'],
            $data['service_code']
        );

        foreach ($data as $key => $value) {
            $shipment->$key = $value;
        }

        $shipment->setServiceType('custom');

        $fromAddress = $this->getFromAddress($data);
        $toAddress = $this->getToAddress($data);

        $result = $delivereaClient->newShipment($shipment, $fromAddress, $toAddress)->toArray();

        if ($callback instanceof \Closure) {
            $callback($result);
        }

        return $result;
    }

    /**
     * @param array $data
     * @param \Closure $callback
     * @return \Deliverea\Response\NewCollectionResponse|string
     */
    public function newCollection($data, $callback = null)
    {
        $delivereaClient = $this->getDelivereaClient();

        $class = '\Deliverea\Model\Collection';

        /** @var \Deliverea\Model\Collection $collection */
        $collection = new $class(
            $data['collection_client_ref'],
            $data['collection_date'],
            $data['carrier_code'],
            $data['service_code'],
            $data['hour_start_1'],
            $data['hour_end_1'],
            $data['shipping_dlvr_ref']
        );

        foreach ($data as $key => $value) {
            $collection->$key = $value;
        }

        $fromAddress = $this->getFromAddress($data);
        $toAddress = $this->getToAddress($data);

        $result = $delivereaClient->newCollection($collection, $fromAddress, $toAddress)->toArray();

        if ($callback instanceof \Closure) {
            $callback($result);
        }

        return $result;
    }

    /**
     * @param $reference
     *
     * @return \Deliverea\Response\GetShipmentLabelResponse|string
     */
    public function getShipmentLabel($reference)
    {
        $delivereaClient = $this->getDelivereaClient();

        return $delivereaClient->getShipmentLabel($reference);
    }

    /**
     * return a zip
     * @param $references
     */
    public function exportLabels($references)
    {
        $fileName = 'etiquetas_' . date_format(new \DateTime('now'), 'Ymd') . '.zip';
        $file = "/tmp/" . $fileName;
        $zip = new \ZipArchive();
        $zip->open($file, \ZipArchive::OVERWRITE);

        foreach ($references as $reference) {
            try {
                $label = $this->getShipmentLabel($reference)->getLabelRaw();
                $zip->addFromString($reference . '.pdf', base64_decode($label));
            } catch (\Exception $e) {
            }
        }

        $zip->close();
        header('Content-Type: text/html');
        header('Content-Length: ' . filesize($file));
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        readfile($file);
        unlink($file);
    }

    /**
     * @param array $data
     * @return array|string
     */
    public function getServiceInfo($data)
    {
        $delivereaClient = $this->getDelivereaClient();

        return $delivereaClient->getServiceInfo(
            $data['carrier_code'],
            $data['service_code'],
            $data['from_country_code'],
            $data['from_zip_code'],
            $data['to_country_code'],
            $data['to_zip_code']
        )->toArray();
    }

    public function getClientCarriers()
    {
        $delivereaClient = $this->getDelivereaClient();
        $carriers = $delivereaClient->getClientCarriers();

        return $carriers->toArray();
    }

    /**
     * @param string $carrierCode
     * @return array
     */
    public function getClientServices($carrierCode)
    {
        $delivereaClient = $this->getDelivereaClient();
        $services = $delivereaClient->getClientServices($carrierCode, null, null, null, 1);

        return $services->toArray();
    }

    /**
     * @return array
     */
    public function getAddress()
    {
        $delivereaClient = $this->getDelivereaClient();
        $addresses = $delivereaClient->getAddresses();

        return $addresses->toArray();
    }

    /**
     * Get Cutoff Hours method can vary, do a previous request to get-service-info to see what parameters are needed
     * @param array $data
     * @return mixed
     */
    public function getCutoffHours($data)
    {
        $delivereaClient = $this->getDelivereaClient();

        return $delivereaClient->getCollectionCutoffHour($data)->toArray();
    }

    /**
     * @param $data
     * @return \Deliverea\Model\Address
     */
    private function getToAddress($data)
    {
        $class = '\Deliverea\Model\Address';

        /** @var \Deliverea\Model\Address $toAddress */
        $toAddress = new $class(
            $data['to_name'],
            $data['to_address'],
            $data['to_city'],
            $data['to_zip_code'],
            $data['to_country_code'],
            $data['to_phone']
        );

        if (!empty($data['to_nif'])) {
            $toAddress->setNif($data['to_nif']);
        }

        if (!empty($data['to_email'])) {
            $toAddress->setEmail($data['to_email']);
        }

        if (!empty($data['to_observations'])) {
            $toAddress->setObservations($data['to_observations']);
        }

        return $toAddress;
    }

    /**
     * @param $data
     * @return \Deliverea\Model\Address
     */
    private function getFromAddress($data)
    {
        $class = '\Deliverea\Model\Address';

        /** @var \Deliverea\Model\Address $fromAddress */
        $fromAddress = new $class(
            $data['from_name'],
            $data['from_address'],
            $data['from_city'],
            $data['from_zip_code'],
            $data['from_country_code'],
            $data['from_phone']
        );

        if (!empty($data['from_nif'])) {
            $fromAddress->setNif($data['from_nif']);
        }

        if (!empty($data['from_email'])) {
            $fromAddress->setEmail($data['from_email']);
        }

        if (!empty($data['from_observations'])) {
            $fromAddress->setObservations($data['from_observations']);
        }

        return $fromAddress;
    }
}
