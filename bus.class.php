<?php

/**
 * Class Bus
 * It can move in both directions; close and open doors; take in and out passengers
 *
 * Some methods and properties are set as protected for possibility to extend current class
 */
class Bus {

    private $stop; // stop's number 0-$stops
    protected $stops = 5; // max stops number in one direction
    public $passengers = 0; // amount of passengers in the bus
    protected $passengers_max = 24; // max amount; it can be changed in another bus modification, if it will extent current bus
    protected $wait = 60; // how long bus waits passengers at stop (in seconds)
    private $doors_open;
    private $direction = true;
    private $passengers_in = 0; // amount of passengers entering the bus
    private $passengers_out = 0; // amount of passengers leaving the bus


    function __construct()
    {
        $this->stop = 0;
    }

    /**
     * Bus moves to next stop
     *
     * @return void
     */
    public function run()
    {
        if ($this->stop < $this->stops && $this->direction) {
            $this->stop++;
        } elseif ($this->stop > 0) {
            $this->stop--;
        }

        if ($this->stop === 0) {
            $this->direction = true;
        }
        if ($this->stop === $this->stops) {
            $this->direction = false;
        }

    }

    /**
     * Define bus actions at stop
     *
     * @return void
     */
    public function stop()
    {
        $this->openDoors();
        $this->passengers = $this->passengers - $this->passengers_out;

        /*
         * if bus has not enough passengers it let them in
         * if after that it still not full, it waits 1min and again takes passengers
         */
        if ($this->canTakePassengers()) {
            $this->passengers += $this->passengers_in;
            if ($this->canTakePassengers()) {
                sleep($this->wait);
                $this->passengers += $this->passengers_in;
            }
        }

        $this->closeDoors();
        $this->run();
    }

    /** Open bus doors
     *
     * @return void
     */
    private function openDoors()
    {
        $this->doors_open = true;
    }

    /** Close bus doors
     *
     * @return void
     */
    private function closeDoors()
    {
        $this->doors_open = false;
    }

    /**
     * Passengers in to the bus
     *
     * @return void
     */
    public function passengersIn($passengers)
    {
        if (!$this->doors_open) return;
        $max_passengers = $this->passengers_max - $this->passengers;
        if ($passengers > $max_passengers) {
            $in = $max_passengers;
        } else {
            $in = $passengers;
        }
        $this->passengers_in = $in;
    }

    /**
     * Passengers out of the bus
     *
     * @return void
     */
    public function passengersOut($passengers)
    {
        if (!$this->doors_open) return;
        $this->passengers_out = ($this->passengers >= $passengers)?$passengers:$this->passengers;
    }

    /**
     * Check if bus can take passengers in;
     *
     * @return bool
     */
    protected function canTakePassengers()
    {
        $pass_diff = $this->passengers_max - $this->passengers;
        if ($pass_diff == 0) return false;
        return true;
    }
}