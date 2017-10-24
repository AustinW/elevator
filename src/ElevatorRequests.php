<?php

namespace AustinW\Elevator;

class ElevatorRequests
{
    protected $requests = [];

    protected $schedulingAlgo;

    public function __construct(SchedulingAlgorithm $algo)
    {
        $this->schedulingAlgo = $algo;
    }

    public function addRequest(ElevatorRequest $request)
    {
        $this->requests[] = $request;
    }

    public function nextRequest()
    {
        if (empty($this->requests)) {
            return null;
        }

        $request = $this->requests[0];
        $this->requests = array_shift($this->requests);

        return $request;
    }

    /**
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param array $requests
     * @return ElevatorRequests
     */
    public function setRequests($requests)
    {
        $this->requests = $requests;
    }


}