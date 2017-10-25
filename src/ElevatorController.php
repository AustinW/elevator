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
        while(!$this->requests->isEmpty()) {
            $request = $this->requests->nextRequest();

            $this->processRequest($request);
        }
    }

    public function shutDown()
    {

    }

    public function processRequest(ElevatorRequest $request)
    {
        $served = false;

        // if available pick a standing elevator for this floor.
        foreach ($this->elevators as $elevator) {

            /** @var Elevator $elevator */
            if ($elevator->isStanding() && $elevator->getCurrentFloor() === $request->getFloor()) {
                $elevator->moveTo($request);
                $served = true;
            }
        }

        // else pick an elevator moving to this floor.
        if (!$served) {
            foreach ($this->elevators as $elevator) {

                /** @var Elevator $elevator */
                if (!$served && $request->getFloor() > $elevator->getCurrentFloor() && $elevator->isMoving('UP') && $elevator->getMovingTo() >= $request->getFloor()) {
                    echo 'on the way';
                }
            }
        }

        if (!$served) {
            foreach ($this->elevators as $elevator) {

                /** @var Elevator $elevator */
                if (!$served && $elevator->isStanding()) {
                    $elevator->moveTo($request);
                    $served = true;
                }
            }
        }


        // else pick a standing elevator on another floor.

    }
}