<?php

namespace AustinW\Elevator\Button;

use AustinW\Elevator\Elevator;
use AustinW\Elevator\ElevatorRequest;

class ElevatorButton
{
    protected $elevator;

    protected $floor;

    /**
     * ElevatorButton constructor.
     * @param Elevator $elevator
     * @param $floor
     */
    public function __construct(Elevator $elevator, $floor)
    {
        $this->elevator = $elevator;
        $this->floor = $floor;
    }
    
    public function press()
    {
        if ($this->elevator->getCurrentFloor() === $this->floor) {
            return;
        }

        $direction = ($this->elevator->getCurrentFloor() < $this->floor) ? 'UP' : 'DOWN';

        $request = new ElevatorRequest($this->floor, $direction);

        $this->elevator->addNewDestination($request);
    }

    /**
     * @return Elevator
     */
    public function getElevator()
    {
        return $this->elevator;
    }

    /**
     * @return mixed
     */
    public function getFloor()
    {
        return $this->floor;
    }
}
