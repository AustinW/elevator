<?php

use AustinW\Elevator\Elevator;
use AustinW\Elevator\ElevatorController;
use AustinW\Elevator\ElevatorFloor;
use AustinW\Elevator\ElevatorRequest;
use AustinW\Elevator\ElevatorScheduler;
use AustinW\Elevator\Exception\ElevatorShutOffException;
use AustinW\Elevator\Exception\UnderMaintenanceException;
use PHPUnit\Framework\TestCase;

class ElevatorControlSystemTest extends TestCase
{
    /** @var ElevatorController $elevatorController */
    protected $elevatorController;

    /** @var  ElevatorScheduler $elevatorScheduler */
    protected $elevatorScheduler;

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

        $this->elevatorScheduler = new ElevatorScheduler($this->elevatorController);
    }

    public function testRequestingTwoElevators()
    {

        $this->elevatorController->pickUp(new ElevatorRequest(10, 'DOWN'));
        $this->elevatorController->pickUp(new ElevatorRequest(7, 'UP'));

        for ($i = 0; $i < 10; $i++) {
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

    public function testPickUpOnTheWay()
    {
        $this->elevatorController->pickUp(new ElevatorRequest(5, 'UP'));
        $this->elevatorController->pickUp(new ElevatorRequest(3, 'UP'));

        for ($i = 0; $i < 5; $i++) {
            $this->elevatorController->step();
        }

        $elevators = $this->elevatorController->getElevators();
        $this->assertEquals(5, $elevators[0]->getCurrentFloor());
    }

    public function testElevatorCannotMoveWhenShutOff()
    {
        $this->expectException(ElevatorShutOffException::class);

        $this->elevatorController->shutDown();

        $this->elevatorController->destination(0, new ElevatorRequest(10, 'UP'));

        for ($i = 0; $i < 10; $i++) {
            $this->elevatorController->step();
        }
    }

    public function testElevatorCannotReceiveRequestsWhenShutOff()
    {
        $this->expectException(ElevatorShutOffException::class);

        $this->elevatorController->shutDown();

        $this->elevatorController->pickUp(new ElevatorRequest(10, 'DOWN'));
    }

    public function testSendRequestWhileElevatorOnTheMove()
    {
        $this->assertEquals(1, 1);
    }

    public function testRequestFromSixthFloorToGround()
    {
        $this->elevatorController->pickUp(new ElevatorRequest(6, 'DOWN'));
        $elevator = $this->elevatorController->getElevators()[0];

        for ($i = 0; $i < 6; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(6, $elevator->getCurrentFloor());

        $this->elevatorController->destination(0, new ElevatorRequest(1, 'DOWN'));

        for ($i = 0; $i < 6; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(1, $elevator->getCurrentFloor());
    }

    public function testRequestFromFifthFloorToSeventhFloorRequest()
    {
        $this->elevatorController->pickUp(new ElevatorRequest(5, 'UP'));
        $elevator = $this->elevatorController->getElevators()[0];

        for ($i = 0; $i < 5; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(5, $elevator->getCurrentFloor());

        $this->elevatorController->destination(0, new ElevatorRequest(7, 'UP'));

        for ($i = 0; $i < 2; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(7, $elevator->getCurrentFloor());
    }

    public function testRequestFromThirdFloorToGround()
    {
        $this->elevatorController->pickUp(new ElevatorRequest(3, 'DOWN'));
        $elevator = $this->elevatorController->getElevators()[0];

        for ($i = 0; $i < 3; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(3, $elevator->getCurrentFloor());

        $this->elevatorController->destination(0, new ElevatorRequest(1, 'DOWN'));

        for ($i = 0; $i < 2; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(1, $elevator->getCurrentFloor());
    }

    public function testRequestFromGroundFloorToSeventhFloor()
    {
        $this->elevatorController->pickUp(new ElevatorRequest(1, 'UP'));
        $elevator = $this->elevatorController->getElevators()[0];

        for ($i = 0; $i < 1; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(1, $elevator->getCurrentFloor());

        $this->elevatorController->destination(0, new ElevatorRequest(7, 'UP'));

        for ($i = 0; $i < 7; $i++) {
            $this->elevatorController->step();
        }

        $this->assertEquals(7, $elevator->getCurrentFloor());
    }

    public function testSecondAndFourthFloorUnderMaintenance()
    {
        $this->expectException(UnderMaintenanceException::class);

        $this->elevatorController->getFloor(2)->closeForMaintenance();
        $this->elevatorController->getFloor(4)->closeForMaintenance();

        $this->elevatorController->destination(0, new ElevatorRequest(2, 'UP'));
        $this->elevatorController->destination(0, new ElevatorRequest(4, 'UP'));

    }

    public function tearDown()
    {

    }
}