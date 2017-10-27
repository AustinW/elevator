<?php

use AustinW\Elevator\Elevator;
use AustinW\Elevator\ElevatorController;
use AustinW\Elevator\ElevatorFloor;
use AustinW\Elevator\ElevatorRequest;
use AustinW\Elevator\Exception\UnderMaintenanceException;
use PHPUnit\Framework\TestCase;

class ElevatorControlSystemTest extends TestCase
{
    /** @var ElevatorController $elevatorController */
    protected $elevatorController;

    public function setUp()
    {
        $elevators = [
            new Elevator('Elevator 1'),
            new Elevator('Elevator 2')
        ];

        $this->elevatorController = new ElevatorController($elevators);

        $floors = [];
        for ($i = Elevator::MIN_FLOOR; $i <= Elevator::MAX_FLOOR; $i++) {
            $floors[$i] = new ElevatorFloor($this->elevatorController, $i);
        }

        $this->elevatorController->setFloors($floors);

        $this->elevatorController->startUp();
    }

    public function testRequestingTwoElevators()
    {
        $this->elevatorController->pickUp(new ElevatorRequest(10, 'DOWN'));
        $this->elevatorController->pickUp(new ElevatorRequest(7, 'UP'));

        for ($i = Elevator::MIN_FLOOR; $i <= 10; $i++) {
            $this->elevatorController->step();
        }

        $elevators = $this->elevatorController->getElevators();

        $this->assertEquals(10, $elevators[0]->getCurrentFloor());
        $this->assertEquals(7, $elevators[1]->getCurrentFloor());
    }

    public function testSendingElevatorToMultipleDestinations()
    {
        $this->elevatorController->destination(0, new ElevatorRequest(10, 'UP'));
        $this->elevatorController->destination(0, new ElevatorRequest(7, 'UP'));

        for ($i = 0; $i < 10; $i++) {
            $this->elevatorController->step();
        }

        $elevators = $this->elevatorController->getElevators();
        $this->assertEquals(10, $elevators[0]->getCurrentFloor());

        for ($i = 0; $i < 10 - 7; $i++) {
            $this->elevatorController->step();
        }

        $elevators = $this->elevatorController->getElevators();
        $this->assertEquals(7, $elevators[0]->getCurrentFloor());
    }

    public function testSendingElevatorToDestination()
    {
        $this->elevatorController->destination(0, new ElevatorRequest(10, 'UP'));
        for ($i = 0; $i < 10; $i++) {
            $this->elevatorController->step();
        }

        $elevators = $this->elevatorController->getElevators();
        $this->assertEquals(10, $elevators[0]->getCurrentFloor());
    }

    public function testFloorUnderMaintenance()
    {
        $this->expectException(UnderMaintenanceException::class);

        $this->elevatorController->getFloor(2)->closeForMaintenance();
        $this->elevatorController->getFloor(4)->closeForMaintenance();

        $this->elevatorController->destination(0, new ElevatorRequest(2, 'UP'));
        $this->elevatorController->destination(0, new ElevatorRequest(4, 'UP'));

    }

    public function testPickUpOnTheWay()
    {
        $this->elevatorScheduler->pickUp(new ElevatorFloor($this->elevatorController, 10));
    }

    public function tearDown()
    {

    }
}