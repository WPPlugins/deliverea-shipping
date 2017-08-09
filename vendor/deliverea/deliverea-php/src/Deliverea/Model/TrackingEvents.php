<?php
namespace Deliverea\Model;

use Deliverea\Common\ToArrayTrait;

class TrackingEvents extends AbstractDeliverea
{
    use ToArrayTrait;

    /** @var string */
    private $current;

    /** @var array */
    private $tracking_history = [];

    public function __construct(
        TrackingEvent $current,
        $tracking_history = []
    ) {
        $this->current = $current;
        $this->tracking_history = $tracking_history;
    }

    /**
     * @return string
     */
    public function getCurrent()
    {
        return $this->current;
    }

    /**
     * @param string $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * @return array
     */
    public function getTrackingHistory()
    {
        return $this->tracking_history;
    }

    /**
     * @param array $tracking_history
     */
    public function setTrackingHistory($tracking_history)
    {
        $this->tracking_history = $tracking_history;
    }
}