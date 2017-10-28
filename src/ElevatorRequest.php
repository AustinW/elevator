<?php

namespace AustinW\Elevator;

class ElevatorRequest
{
    protected $requestTime;

    protected $floor;

    protected $direction;

    /**
     * ElevatorRequest constructor.
     * @param $floor
     * @param $direction
     */
    public function __construct($floor, $direction)
    {
        $this->requestTime = time();
        $this->floor = $floor;
        $this->direction = $direction;
    }

    /**
     * @return int
     */
    public function getRequestTime()
    {
        return $this->requestTime;
    }

    /**
     * @return mixed
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @param $direction
     * @return bool
     */
    public function isDirection($direction)
    {
        return $this->direction === $direction;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }
}
