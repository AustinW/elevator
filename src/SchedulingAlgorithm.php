<?php

namespace AustinW\Elevator;

class SchedulingAlgorithm
{
    public function shortestSeekFirst(Array $requests)
    {
        if (empty($requests)) {
            return null;
        }

        return $requests[0];
    }
}