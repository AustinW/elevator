<?php

namespace AustinW\Elevator;

class Elevator implements ElevatorStateInterface
{
    protected $state; // up, down, stand, maintenance

    protected $currentFloor;

    protected $floorRequests;

    protected $signals;

    protected $floors;

    public function __construct()
    {

    }

    public function openDoor()
    {

    }

    public function closeDoor()
    {

    }

    public function moveUp($current, $floor)
    {
        while ($current < $floor && $current <= count($this->floors)) {
            $current++;
        }

        return $current;
    }

    public function moveDown()
    {

    }

    public function setSignal($signal)
    {

    }

}