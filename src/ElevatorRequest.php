<?php

namespace AustinW\Elevator;

class ElevatorRequest
{
    protected $requestTime;

    protected $floor;

    protected $direction;

    public function __construct($floor, $direction)
    {
        $this->floor = $floor;

        $this->direction = $direction;

        $this->requestTime = time();
    }
}