<?php
/**
 * Created by PhpStorm.
 * User: austinwhite
 * Date: 10/23/17
 * Time: 3:32 PM
 */

namespace AustinW\Elevator;


class ElevatorController
{
    protected $elevator;

    /**
     * ElevatorController constructor.
     * @param Elevator $elevator
     */
    public function __construct(Elevator $elevator)
    {
        $this->elevator = $elevator;
    }

    public function startUp()
    {

    }

    public function shutDown()
    {

    }
}