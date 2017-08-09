<?php
namespace Deliverea\Model;

use Deliverea\Common\ToArrayTrait;

class DetailedCollection extends AbstractDeliverea
{
    use ToArrayTrait;

    /** @var Collection */
    private $collection;

    /** @var Address */
    private $from;

    /** @var Address */
    private $to;

    /**
     * @param Collection $collection
     * @param Address $from
     * @param Address $to
     */
    public function __construct(
        Collection $collection,
        Address $from = null,
        Address $to = null
    ) {
        $this->collection = $collection;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param Collection $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;
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
}