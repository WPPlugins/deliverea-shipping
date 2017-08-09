<?php

namespace Deliverea\Common;

trait MagicGetAndSetTrait
{
    /**
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        $property = $this->decamelize($property);

        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $method = 'set' . $this->camelize($property);

        if (method_exists($this, $method)) {
            $this->$method($value);
        } else {
            $this->$property = $value;
        }
    }

    function decamelize($word)
    {
        return $word = preg_replace_callback(
            "/(^|[a-z])([A-Z])/",
            function ($m) {
                return strtolower(strlen($m[1]) ? "$m[1]_$m[2]" : "$m[2]");
            },
            $word
        );

    }

    function camelize($word)
    {
        return $word = preg_replace_callback(
            "/(^|_)([a-z])/",
            function ($m) {
                return strtoupper("$m[2]");
            },
            $word
        );

    }
}