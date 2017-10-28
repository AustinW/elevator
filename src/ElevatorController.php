<?php

namespace AustinW\Elevator;

use AustinW\Elevator\Exception\ElevatorShutOffException;
use AustinW\Elevator\Exception\UnderMaintenanceException;

class ElevatorController
{
    /* @var array $elevators */
    protected $elevators;

    protected $status;

    protected $pickupLocations = [];

    protected $floors = [];

    /* @var \Monolog\Logger $logger */
    private $logger;

    /**
     * ElevatorController constructor.
     * @param array      $elevators
     * @param array|null $floors
     */
    public function __construct(array $elevators, array $floors = null)
    {
        foreach ($elevators as $elevator) {
            $elevator->setCurrentFloor(Elevator::MIN_FLOOR);
        }

        $this->elevators = $elevators;

        if ($floors) {
            $this->setFloors($floors);
        }

        $elevatorLog = new ElevatorLog();
        $elevatorLog->setFileOutput('test.log');
        $this->logger = $elevatorLog->getLogger();
    }

    /**
     * Start the elevator up
     */
    public function startUp()
    {
        $this->status = 'ON';
    }

    /**
     * Shut the elevator down
     */
    public function shutDown()
    {
        $this->status = 'OFF';
    }

    /**
     * @param  ElevatorRequest          $request
     * @throws ElevatorShutOffException
     */
    public function pickUp(ElevatorRequest $request)
    {
        if ($this->status === 'OFF') {
            throw new ElevatorShutOffException('Elevator cannot serve requests when the system is shut off');
        }

        $this->pickupLocations[] = $request;
    }

    /**
     * @param $elevatorId
     * @param  ElevatorRequest           $destination
     * @throws ElevatorShutOffException
     * @throws UnderMaintenanceException
     */
    public function destination($elevatorId, ElevatorRequest $destination)
    {
        if ($this->checkForMaintenance($destination->getFloor())) {
            throw new UnderMaintenanceException('Floor is under maintenance and cannot be reached');
        }

        if ($this->status === 'OFF') {
            throw new ElevatorShutOffException('Elevator cannot serve requests when the system is shut off');
        }

        $this->elevators[$elevatorId]->addNewDestination($destination);
    }

    /**
     * Main elevator controller driver
     */
    public function step()
    {
        if ($this->status === 'OFF') {
            throw new ElevatorShutOffException('Cannot serve elevator requests while the elevator is shut off.');
        }

        $this->delegatePickUps();

        // Loop through each elevator
        foreach ($this->elevators as $key => $elevator) {
            /* @var Elevator $elevator */
            switch ($elevator->status()) {
                case 'OCCUPIED':
                    switch ($elevator->direction()) {
                        case 'UP':
                            $this->checkForMaintenance($elevator->getCurrentFloor() + 1);
                            $elevator->moveUp();
                            break;
                        case 'DOWN':
                            $elevator->moveDown();
                            break;
                        case 'HOLD':
                            $this->checkForMaintenance($elevator->getCurrentFloor() + 1);
                            $elevator->popDestination();
                            $elevator->openDoor();
                            break;
                    }
            }
        }
    }

    /**
     * Send pickup requests to the appropriate elevator
     */
    protected function delegatePickUps()
    {
        while (!empty($this->pickupLocations)) {
            // decide which elevator to use to pickup

            /* @var ElevatorRequest $pickupLocation */
            $pickupLocation = $this->pickupLocations[0];
            $pickupLocationServed = false;

            foreach ($this->elevators as $key => $elevator) {
                /* @var Elevator $elevator */

                if (!$pickupLocationServed) {
                    if ($elevator->getCurrentFloor() === $pickupLocation->getFloor() && $elevator->isEmpty()) {
                        $this->logger->addInfo(sprintf('[%s] Adding pickup location on floor %d for elevator already at floor %d', $elevator->getName(), $pickupLocation->getFloor(), $elevator->getCurrentFloor()));
                        $elevator->addNewDestination($pickupLocation);
                        array_shift($this->pickupLocations);
                        $pickupLocationServed = true;
                    } elseif ($this->_elevatorOnTheWay('UP', $elevator, $pickupLocation)) {
                        $this->logger->addInfo(sprintf('[%s] Adding pickup location on floor %d for elevator on the way UP at currently at floor %d', $elevator->getName(), $pickupLocation->getFloor(), $elevator->getCurrentFloor()));
                        $elevator->addNewDestination($pickupLocation);
                        array_shift($this->pickupLocations);
                        $pickupLocationServed = true;
                    } elseif ($this->_elevatorOnTheWay('DOWN', $elevator, $pickupLocation)) {
                        $this->logger->addInfo(sprintf('[%s] Adding pickup location on floor %d for elevator on the way DOWN at currently at floor %d', $elevator->getName(), $pickupLocation->getFloor(), $elevator->getCurrentFloor()));
                        $elevator->addNewDestination($pickupLocation);
                        array_shift($this->pickupLocations);
                        $pickupLocationServed = true;
                    } elseif ($elevator->status() === 'EMPTY') {
                        $this->logger->addInfo(sprintf('[%s] Adding pickup location on floor %d for elevator sitting at floor %d', $elevator->getName(), $pickupLocation->getFloor(), $elevator->getCurrentFloor()));
                        $elevator->addNewDestination($pickupLocation);
                        array_shift($this->pickupLocations);
                        $pickupLocationServed = true;
                    }
                }
            }
        }
    }

    /**
     * @param $direction
     * @param  Elevator        $elevator
     * @param  ElevatorRequest $pickupLocation
     * @return bool
     */
    private function _elevatorOnTheWay($direction, Elevator $elevator, ElevatorRequest $pickupLocation)
    {
        if ($direction === 'UP') {
            return
                $elevator->isOccupied() &&
                $elevator->getCurrentFloor() < $pickupLocation->getFloor() &&
                $elevator->highestDestination() >= $pickupLocation->getFloor() &&
                $pickupLocation->isDirection('UP') &&
                $elevator->destinationDirection('UP');
        } else {
            return
                $elevator->isOccupied() &&
                $elevator->getCurrentFloor() > $pickupLocation->getFloor() &&
                $elevator->lowestDestination() <= $pickupLocation->getFloor() &&
                $pickupLocation->isDirection('DOWN') &&
                $elevator->destinationDirection('DOWN');
        }
    }

    /**
     * @param $floorNumber
     * @return bool
     */
    public function checkForMaintenance($floorNumber)
    {
        return $this->getFloor($floorNumber)->underMaintenance();
    }

    /**
     * @param  int           $index
     * @return ElevatorFloor
     */
    public function getFloor($index)
    {
        return $this->floors[$index];
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

    /**
     * @return array
     */
    public function getElevators()
    {
        return $this->elevators;
    }

    /**
     * @param $index
     * @return Elevator
     */
    public function getElevator($index)
    {
        return $this->elevators[$index];
    }
}
