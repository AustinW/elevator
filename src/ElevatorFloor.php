<?php

namespace AustinW\Elevator;

class ElevatorFloor
{
    protected $floor;

    protected $elevatorController;

    protected $buttons = [];

    protected $status;

    /**
     * ElevatorFloor constructor.
     * @param ElevatorController $elevatorController
     * @param $floor
     */
    public function __construct(ElevatorController $elevatorController, $floor)
    {
        $this->elevatorController = $elevatorController;
        $this->floor = $floor;
    }

    /**
     *
     */
    public function pressUp()
    {
        $this->requestPickup('UP');
    }

    /**
     *
     */
    public function pressDown()
    {
        $this->requestPickup('DOWN');
    }

    /**
     *
     */
    protected function requestPickup($direction)
    {
        // Should fire an event, not be tied to ElevatorController
        $this->elevatorController->pickUp(new ElevatorRequest($this->floor, $direction));
    }

    /**
     * @return bool
     */
    public function isOpen()
    {
        return $this->status === 'OPEN';
    }

    /**
     *
     */
    public function reopen()
    {
        $this->status = 'OPEN';
    }

    /**
     * @return bool
     */
    public function underMaintenance()
    {
        return $this->status === 'MAINTENANCE';
    }

    /**
     *
     */
    public function closeForMaintenance()
    {
        $this->status = 'MAINTENANCE';
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}