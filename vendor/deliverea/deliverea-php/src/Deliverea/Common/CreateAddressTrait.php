<?php

namespace Deliverea\Common;

use \Deliverea\Model\Address;

trait CreateAddressTrait
{
    /**
     * @param $prefix
     * @param $data
     * @return Address
     */
    private function createAddress($prefix, $data)
    {
        $data = (array)$data;

        if ($prefix) {
            $prefix .= '_';
        }

        if (!empty($data[$prefix . 'name'])) {
            $name = $this->getValue($data, $prefix . 'name', '');
        } else {
            $name = $this->getValue($data, $prefix . 'alias', '');
        }

        $address = new Address(
            $name,
            $this->getValue($data, $prefix . 'address', ''),
            $this->getValue($data, $prefix . 'city', ''),
            $this->getValue($data, $prefix . 'zip_code', ''),
            $this->getValue($data, $prefix . 'country_code', ''),
            $this->getValue($data, $prefix . 'phone', '')
        );

        $address->setAddress($this->getValue($data, $prefix . 'address', ''));
        $address->setNif($this->getValue($data, $prefix . 'nif', ''));
        $address->setAttn($this->getValue($data, $prefix . 'attn', ''));
        $address->setEmail($this->getValue($data, $prefix . 'email', ''));
        $address->setObservations($this->getValue($data, $prefix . 'observations', ''));

        return $address;
    }
}