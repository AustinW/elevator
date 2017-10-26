<?php

require_once 'bootstrap.php';

use AustinW\Elevator\Elevator;
use AustinW\Elevator\ElevatorController;
use AustinW\Elevator\Button\FloorButton;
use AustinW\Elevator\ElevatorFloor;
use AustinW\Elevator\ElevatorRequest;


$elevators = [
    new Elevator('Elevator 1'),
    new Elevator('Elevator 2')
];


$elevatorControlSystem = new ElevatorController($elevators);

$floors = [];
for ($i = Elevator::MIN_FLOOR; $i <= Elevator::MAX_FLOOR; $i++) {
    $floors[$i] = new ElevatorFloor($elevatorControlSystem, $i);
}

$elevatorControlSystem->setFloors($floors);
$elevatorControlSystem->startUp();

$elevatorControlSystem->pickUp(new ElevatorRequest(10));
$elevatorControlSystem->pickUp(new ElevatorRequest(7));

for ($i = 0; $i <= 10; $i++) {
    $elevatorControlSystem->step();
}