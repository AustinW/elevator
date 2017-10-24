<?php

namespace AustinW\Elevator;

class Elevator implements ElevatorStateInterface
{
    const MIN_FLOOR = 0;
    const MAX_FLOOR = 20;

    protected $currentFloor;

    protected $state;

    protected $states = ['UP', 'DOWN', 'STAND', 'MAINTENANCE'];

    protected $floorRequests;

    protected $signals;

    public function __construct()
    {
        $this->state = 'STAND';
    }

    public function openDoor()
    {
        log_msg('Opening the door...');
    }

    public function closeDoor()
    {
        log_msg('Closing the door...');
    }

    public function moveUp($floor)
    {
        log_msg('Going up...');

        $this->setState('UP');

        while ($this->currentFloor < $floor && $this->currentFloor <= self::MAX_FLOOR) {
            $this->currentFloor++;
            log_msg('Current floor: ' . $this->currentFloor);
        }

        return $this->currentFloor;
    }

    public function moveDown($floor)
    {
        log_msg('Going down...');

        $this->setState('DOWN');

        while ($this->currentFloor > $floor && $this->currentFloor >= self::MIN_FLOOR) {
            $this->currentFloor--;
            log_msg('Current floor: ' . $this->currentFloor);
        }

        return $this->currentFloor;
    }

    public function setSignal($signal)
    {
        if (in_array($signal, $this->signals)) {
            $this->signal = $signal;
        }
    }

    /**
     * @return mixed
     */
    public function getCurrentFloor()
    {
        return $this->currentFloor;
    }

    /**
     * @param mixed $currentFloor
     */
    public function setCurrentFloor($currentFloor)
    {
        $this->currentFloor = $currentFloor;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @throws \Exception
     */
    public function setState($state)
    {
        if (in_array($state, $this->validStates)) {
            $this->state = $state;
        } else {
            throw new \Exception('Could not interpret the assignment of state: "' . $state . '"');
        }
    }

}