<?php

namespace Deliverea\Model;

abstract class AbstractDeliverea
{
    /**
     * Maps the response to it's own properties
     * @param $response
     * @return mixed
     */
    function map($response)
    {
        $keys = array_keys((array)$response);

        foreach ($keys as $key) {
            if (!empty($response->$key)) {
                $this->$key = $response->$key;
            }
        }

        return $this;
    }

    abstract function toArray();

    protected function getValue($data, $key, $default)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }

        if (empty($data[$key])) {
            $data[$key] = $default;
        };

        return $data[$key];
    }
}