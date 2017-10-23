<?php

namespace AustinW\Elevator;


Interface ElevatorStateInterface
{
    const CLOSE = 0;
    const OPEN = 1;
    const MOVING = 2;
    const ALARM = 3;
    public function setSignal($signal);
}