<?php

namespace AustinW\Elevator\Button;

use AustinW\Elevator\Elevator;
use AustinW\Elevator\ElevatorRequest;
use AustinW\Elevator\ElevatorRequests;

class ElevatorButton
{
    protected $elevatorRequests;

    protected $elevator;

    /**
     * ElevatorButton constructor.
     * @param ElevatorRequests $elevatorRequests
     * @param Elevator $elevator
     */
    public function __construct(ElevatorRequests $elevatorRequests, Elevator $elevator)
    {
        $this->elevatorRequests = $elevatorRequests;
        $this->elevator = $elevator;
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

    /**
     * @return Elevator
     */
    public function getElevator()
    {
        return $this->elevator;
    }
}