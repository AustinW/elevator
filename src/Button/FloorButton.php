<?php

namespace AustinW\Elevator\Button;

use AustinW\Elevator\ElevatorRequest;
use AustinW\Elevator\ElevatorRequests;

class FloorButton
{
    protected $floor;

    protected $elevatorRequests;

    /**
     * ElevatorButton constructor.
     * @param ElevatorRequests $elevatorRequests
     * @param $floor
     */
    public function __construct(ElevatorRequests $elevatorRequests, $floor)
    {
        $this->elevatorRequests = $elevatorRequests;

        $this->floor = $floor;
    }

    /**
     * @param $direction
     */
    public function makeRequest($direction)
    {
        log_msg('New request from floor ' . $this->floor . ' to go ' . strtolower($direction) . '...');

        $request = new ElevatorRequest($this->floor, $direction);

        $this->elevatorRequests->addRequest($request);
    }
}