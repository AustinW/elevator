<?php

namespace AustinW\Elevator\Button;

use AustinW\Elevator\ElevatorRequest;
use AustinW\Elevator\ElevatorRequests;

class ElevatorButton
{
    protected $elevatorRequests;

    /**
     * ElevatorButton constructor.
     * @param ElevatorRequests $elevatorRequests
     */
    public function __construct(ElevatorRequests $elevatorRequests)
    {
        $this->elevatorRequests = $elevatorRequests;
    }

    /**
     * @param $floor
     * @param $direction
     */
    public function makeRequest($direction, $floor)
    {
        $request = new ElevatorRequest($floor, $direction);

        $this->elevatorRequests->addRequest($request);
    }
}