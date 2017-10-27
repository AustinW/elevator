<?php

namespace AustinW\Elevator;

class ElevatorRequest
{
    protected $requestTime;

    protected $floor;

    protected $direction;

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
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }


}