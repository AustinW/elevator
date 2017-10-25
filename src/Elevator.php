<?php

namespace AustinW\Elevator;

class Elevator implements ElevatorStateInterface
{
    const MIN_FLOOR = 0;
    const MAX_FLOOR = 11;
    const SPEED = 1; // seconds to move between floors
    const OPEN_CLOSE = 5; // seconds for doors to open and close

    protected $currentFloor;

    protected $state;

    protected $validStates = ['UP', 'DOWN', 'STAND', 'MAINTENANCE'];

    protected $floorRequests = [];

    protected $signals;

    protected $movingTo;

    protected $name;

    public function __construct($name = '')
    {
        $this->state = 'STAND';
        $this->name = $name;
    }

    public function openDoor()
    {
        log_msg(sprintf('Opening the door at floor %d...', $this->currentFloor));
        sleep(self::OPEN_CLOSE);
        $this->closeDoor();
    }

    public function closeDoor()
    {
        log_msg('Closing the door...');
    }

    protected function moveUp()
    {
        log_msg('Going up...');

        $this->setState('UP');
        $this->setMovingTo($this->floorRequests[count($this->floorRequests) - 1]->getFloor());

        var_dump($this->floorRequests);

        while (!empty($this->floorRequests) && $this->currentFloor < self::MAX_FLOOR) {
            $targetFloor = $this->floorRequests[0];
            $this->currentFloor++;
            sleep(self::SPEED);
            log_msg(sprintf('[%s] Current floor: %d', $this->name, $this->currentFloor));

            if ($targetFloor->getFloor() === $this->currentFloor) {
                array_shift($this->floorRequests);
                $this->openDoor();
            }
        }

        $this->setState('STAND');

        return $this->currentFloor;
    }

    protected function moveDown()
    {
        log_msg('Going down...');

        $this->setState('DOWN');
        $this->setMovingTo($this->floorRequests[0]->getFloor());

        while (!empty($this->floorRequests) && $this->currentFloor > 0) {
            $targetFloor = $this->floorRequests[count($this->floorRequests) - 1];
            $this->currentFloor--;
            sleep(self::SPEED);
            log_msg(sprintf('[%s] Current floor: %d', $this->name, $this->currentFloor));

            if ($targetFloor->getFloor() === $this->currentFloor) {
                array_pop($this->floorRequests);
                $this->openDoor();
            }
        }

        $this->setState('STAND');

        return $this->currentFloor;
    }

    public function moveTo(ElevatorRequest $request)
    {
        $direction = ($request->getFloor() > $this->getCurrentFloor()) ? 'UP' : 'DOWN';

        $this->floorRequests[] = $request;

        if ($direction === 'UP') {
            usort($this->floorRequests, function(ElevatorRequest $a, ElevatorRequest $b) {
                if ($a->getFloor() == $b->getFloor()) {
                    return 0;
                }
                return ($a->getFloor() < $b->getFloor()) ? -1 : 1;
            });
        } else if ($direction === 'DOWN') {
            usort($this->floorRequests, function(ElevatorRequest $a, ElevatorRequest $b) {
                if ($a->getFloor() == $b->getFloor()) {
                    return 0;
                }
                return ($a->getFloor() > $b->getFloor()) ? -1 : 1;
            });
        }

        $this->moveUp();
    }

    public function queueMove(ElevatorRequest $request, $direction)
    {

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

    public function isStanding()
    {
        return $this->state === 'STAND';
    }

    public function isMoving($direction = null)
    {
        if ($direction) {
            return $this->state === strtoupper($direction);
        } else {
            return $this->state === 'UP' || $this->state === 'DOWN';
        }
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

    /**
     * @return mixed
     */
    public function getMovingTo()
    {
        return $this->movingTo;
    }

    /**
     * @param mixed $movingTo
     */
    public function setMovingTo($movingTo)
    {
        $this->movingTo = $movingTo;
    }
}