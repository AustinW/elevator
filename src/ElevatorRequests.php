<?php

namespace AustinW\Elevator;

class ElevatorRequests
{
    protected $requests;

    protected $schedulingAlgo;

    public function __construct(SchedulingAlgorithm $algo)
    {
        $this->schedulingAlgo = $algo;
    }

    public function addRequest()
    {

    }

    public function removeRequest()
    {

    }

    public function nextRequest()
    {

    }
}