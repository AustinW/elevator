<?php

namespace AustinW\Elevator;

use AustinW\Elevator\Button\ElevatorButton;

class Elevator
{
    const MIN_FLOOR = 1;
    const MAX_FLOOR = 11;
    const SPEED = 0; // seconds to move between floors
    const OPEN_CLOSE = 0; // seconds for doors to open and close

    protected $currentFloor;

    protected $state;

    protected $validStates = ['UP', 'DOWN', 'STAND', 'MAINTENANCE'];

    protected $buttons = [];

    protected $destinationFloors = [];

    protected $name;

    /** @var \Monolog\Logger $logger */
    private $logger;

    public function __construct($name = '')
    {
        $this->name = $name;

        for ($i = self::MIN_FLOOR; $i <= self::MAX_FLOOR; $i++) {
            $this->buttons[$i] = new ElevatorButton($this, $i);
        }

        $elevatorLog = new ElevatorLog();
        $elevatorLog->setNoLog();
        $this->logger = $elevatorLog->getLogger();
    }

    public function openDoor()
    {
        $this->logger->addInfo(sprintf('[%s] Opening the door...', $this->getName()));
        sleep(self::OPEN_CLOSE);
        $this->closeDoor();
    }

    public function closeDoor()
    {
        $this->logger->addInfo(sprintf('[%s] Closing the door...', $this->getName()));
    }

    public function moveUp() {
        $this->logger->addInfo(sprintf('[%s] Current floor: %d', $this->name, $this->currentFloor + 1));
        sleep(self::SPEED);
        return $this->currentFloor++;
    }

    public function moveDown() {
        $this->logger->addInfo(sprintf('[%s] Current floor: %d', $this->name, $this->currentFloor - 1));
        sleep(self::SPEED);
        return $this->currentFloor--;
    }

    /**
     * @return ElevatorRequest|null
     */
    public function nextDestination()
    {
        return (!empty($this->destinationFloors))
            ? $this->destinationFloors[0]
            : null;
    }

    public function addNewDestination(ElevatorRequest $destination)
    {
        $this->destinationFloors[] = $destination;
    }

    public function popDestination() {
        return array_shift($this->destinationFloors);
    }

    public function direction()
    {
        if (count($this->destinationFloors) > 0) {
            if ($this->currentFloor < $this->nextDestination()->getFloor()) {
                return 'UP';
            } else if ($this->currentFloor > $this->nextDestination()->getFloor()) {
                return 'DOWN';
            }
        }

        return 'HOLD';
    }

    public function status()
    {
        return (count($this->destinationFloors) > 0) ? 'OCCUPIED' : 'EMPTY';
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
    public function getName()
    {
        return $this->name;
    }
}