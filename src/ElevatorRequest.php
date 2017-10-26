<?php

namespace AustinW\Elevator;

class ElevatorRequest
{
    protected $requestTime;

    protected $floor;

    public function __construct($floor)
    {
        $this->floor = $floor;

        $this->requestTime = time();
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
}