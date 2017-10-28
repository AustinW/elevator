<?php

namespace AustinW\Elevator;

use AustinW\Elevator\Button\ElevatorButton;
use AustinW\Elevator\Exception\ElevatorAlarmException;

class Elevator
{
    const MIN_FLOOR = 1;
    const MAX_FLOOR = 11;
    const SPEED = 0; // seconds to move between floors
    const OPEN_CLOSE = 0; // seconds for doors to open and close

    protected $currentFloor;

    protected $state = '';

    protected $buttons = [];

    protected $destinationFloors = [];

    protected $name;

    /* @var \Monolog\Logger $logger */
    private $logger;

    /**
     * Elevator constructor.
     * @param string $name
     */
    public function __construct($name = '')
    {
        $this->name = $name;

        for ($i = self::MIN_FLOOR; $i <= self::MAX_FLOOR; $i++) {
            $this->buttons[$i] = new ElevatorButton($this, $i);
        }

        $elevatorLog = new ElevatorLog();
        $elevatorLog->setTerminalOutput();
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

    /**
     * @return int
     */
    public function moveUp()
    {
        $this->logger->addInfo(sprintf('[%s] Current floor: %d', $this->name, $this->currentFloor + 1));
        sleep(self::SPEED);
        return $this->currentFloor++;
    }

    /**
     * @return int
     */
    public function moveDown()
    {
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

    /**
     * @param ElevatorRequest $destination
     */
    public function addNewDestination(ElevatorRequest $destination)
    {
        $this->destinationFloors[] = $destination;

        if ($destination->isDirection('UP')) {
            sort($this->destinationFloors);
        } else {
            rsort($this->destinationFloors);
        }
    }

    /**
     * @return mixed
     */
    public function popDestination()
    {
        return array_shift($this->destinationFloors);
    }

    /**
     * @param  null|string $direction
     * @return null|string
     */
    public function destinationDirection($direction = null)
    {
        if (count($this->destinationFloors) > 0) {
            if ($direction) {
                return $this->nextDestination()->getDirection() === $direction;
            } else {
                return $this->nextDestination()->getDirection();
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function direction()
    {
        if (count($this->destinationFloors) > 0) {
            if ($this->currentFloor < $this->nextDestination()->getFloor()) {
                return 'UP';
            } elseif ($this->currentFloor > $this->nextDestination()->getFloor()) {
                return 'DOWN';
            } else {
                // current floor == destination->floor
                return 'HOLD';
            }
        }

        return 'HOLD';
    }

    /**
     * @return mixed
     */
    public function highestDestination()
    {
        return max(array_map(function (ElevatorRequest $floor) {
            return $floor->getFloor();
        }, $this->destinationFloors));
    }

    /**
     * @return mixed
     */
    public function lowestDestination()
    {
        return min(array_map(function (ElevatorRequest $floor) {
            return $floor->getFloor();
        }, $this->destinationFloors));
    }

    /**
     * @throws ElevatorAlarmException
     * @return string
     */
    public function status()
    {
        if ($this->state === 'ALARM') {
            throw new ElevatorAlarmException('Elevator alarm is sounding. Movement halted.');
        }

        return (count($this->destinationFloors) > 0) ? 'OCCUPIED' : 'EMPTY';
    }

    public function isEmpty()
    {
        return $this->status() === 'EMPTY';
    }

    public function isOccupied()
    {
        return ! $this->isEmpty();
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

    /**
     * @return array
     */
    public function getDestinations()
    {
        return $this->destinationFloors;
    }

    public function soundAlarm()
    {
        $this->state = 'ALARM';
    }
}
