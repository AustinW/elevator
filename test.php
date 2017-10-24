<?php

require_once 'bootstrap.php';

use AustinW\Elevator\Elevator;
use AustinW\Elevator\ElevatorController;
use AustinW\Elevator\ElevatorRequests;
use AustinW\Elevator\SchedulingAlgorithm;
use AustinW\Elevator\Button\FloorButton;

$elevators = [
    new Elevator(),
    new Elevator(),
    new Elevator()
];

$requests = new ElevatorRequests(new SchedulingAlgorithm());
$controller = new ElevatorController($elevators, $requests);

$tenthFloor = new FloorButton($requests, 10);

//$elevator->moveUp(5);
//$elevator->moveDown(2);

$tenthFloor->makeRequest('UP');

$controller->startUp();