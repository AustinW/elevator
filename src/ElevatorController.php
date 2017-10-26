<?php

namespace AustinW\Elevator;

use AustinW\Elevator\Button\FloorButton;

class ElevatorController
{
    protected $elevators;

    protected $status;

    protected $pickupLocations = [];

    protected $floors = [];

    /**
     * ElevatorController constructor.
     * @param array $elevators
     * @param array|null $floors
     */
    public function __construct(Array $elevators, Array $floors = null)
    {
        foreach($elevators as $elevator) {
            $elevator->setCurrentFloor(Elevator::MIN_FLOOR);
        }

        $this->elevators = $elevators;

        if ($floors) {
            $this->setFloors($floors);
        }
    }

    public function startUp()
    {
        $this->status = 'ON';
    }

    public function shutDown()
    {
        $this->status = 'OFF';
    }

    public function pickUp(ElevatorRequest $request)
    {
        $this->pickupLocations[] = $request;
    }

    public function destination($elevatorId, $destination)
    {
        $this->elevators[$elevatorId]->addNewDestination($destination);
    }

    public function step()
    {
        // Loop through each elevator

        foreach ($this->elevators as $key => $elevator) {
            /* @var Elevator $elevator */
            // Algorithm
            switch ($elevator->status()) {
                case 'EMPTY':
                    if (!empty($this->pickupLocations)) {
                        $elevator->addNewDestination(array_shift($this->pickupLocations));
                    }
                    break;

                case 'OCCUPIED':
                    switch ($elevator->direction()) {
                        case 'UP':
                            $elevator->moveUp();
                            break;
                        case 'DOWN':
                            $elevator->moveDown();
                            break;
                        case 'HOLD':
                            $elevator->popDestination();
                            $elevator->openDoor();
                            break;
                    }

                    if ($elevator->direction() === 'UP') {
                        break;
                    }
            }
        }
    }

    /**
     * @return array
     */
    public function getFloors()
    {
        return $this->floors;
    }

    /**
     * @param array $floors
     */
    public function setFloors($floors)
    {
        $this->floors = $floors;
    }

//    public function processRequest(ElevatorRequest $request)
//    {
//        $served = false;
//
//        // if available pick a standing elevator for this floor.
//        foreach ($this->elevators as $elevator) {
//
//            /** @var Elevator $elevator */
//            if ($elevator->isStanding() && $elevator->getCurrentFloor() === $request->getFloor()) {
//                $elevator->moveTo($request);
//                $served = true;
//            }
//        }
//
//        // else pick an elevator moving to this floor.
//        if (!$served) {
//            foreach ($this->elevators as $elevator) {
//
//                /** @var Elevator $elevator */
//                if (!$served && $request->getFloor() > $elevator->getCurrentFloor() && $elevator->isMoving('UP') && $elevator->getMovingTo() >= $request->getFloor()) {
//                    echo 'on the way';
//                }
//            }
//        }
//
//        if (!$served) {
//            foreach ($this->elevators as $elevator) {
//
//                /** @var Elevator $elevator */
//                if (!$served && $elevator->isStanding()) {
//                    $elevator->moveTo($request);
//                    $served = true;
//                }
//            }
//        }
//
//
//        // else pick a standing elevator on another floor.
//
//    }

    /**
     * @return array
     */
    public function getElevators()
    {
        return $this->elevators;
    }
}