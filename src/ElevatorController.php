<?php

namespace AustinW\Elevator;

class ElevatorController
{
    protected $elevators;

    protected $requests;

    /**
     * ElevatorController constructor.
     * @param array $elevators
     * @param ElevatorRequests $requests
     * @internal param Elevator $elevator
     */
    public function __construct(Array $elevators, ElevatorRequests $requests)
    {
        foreach($elevators as $elevator) {
            $elevator->setCurrentFloor(0);
        }

        $this->elevators = $elevators;
        $this->requests = $requests;
    }

    public function startUp()
    {
        $this->requests->nextRequest();
    }

    public function shutDown()
    {

    }
}