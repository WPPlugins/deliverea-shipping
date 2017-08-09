<?php

namespace Deliverea\Response;

use Deliverea\Common\ToArrayTrait;

class GetCollectionCutoffHourResponse extends AbstractResponse
{
    use ToArrayTrait;

    protected $country_code;

    protected $zip_code;

    protected $cutoff;
}