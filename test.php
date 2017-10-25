<?php

require_once 'bootstrap.php';

use AustinW\Elevator\Elevator;
use AustinW\Elevator\ElevatorController;
use AustinW\Elevator\ElevatorRequests;
use AustinW\Elevator\SchedulingAlgorithm;
use AustinW\Elevator\Button\FloorButton;

$elevators = [
    new Elevator('Elevator 1'),
    new Elevator('Elevator 2')
];

$requests = new ElevatorRequests(new SchedulingAlgorithm());
$controller = new ElevatorController($elevators, $requests);

$groundFloor = new FloorButton($requests, 0);
$firstFloor = new FloorButton($requests, 1);
$secondFloor = new FloorButton($requests, 2);
$thirdFloor = new FloorButton($requests, 3);
$fourthFloor = new FloorButton($requests, 4);
$fifthFloor = new FloorButton($requests, 5);
$sixthFloor = new FloorButton($requests, 6);
$seventhFloor = new FloorButton($requests, 7);
$eighthFloor = new FloorButton($requests, 8);
$ninthFloor = new FloorButton($requests, 9);
$tenthFloor = new FloorButton($requests, 10);

//$elevator->moveUp(5);
//$elevator->moveDown(2);

$controller->startUp();

$tenthFloor->makeRequest('UP');
$secondFloor->makeRequest('UP');

