<?php

namespace ReSettlers\Profile;

use ReSettlers\Component\Worker;

abstract class WorkerSet
{
    /**
     * @var ReSettlers\Component\Worker
     */
    protected $worker;

    /**
     * Get building count.
     * @return int
     */
    abstract public function getBuildingCount();

    /**
     * Get number of items built by cycle.
     * @return int
     */
    abstract public function getCount();

    /**
     * Get worker.
     * @return ReSettlers\Component\Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Shortcut to get the resource without fetching the worker instance.
     * @return ReSettlers\Component\Resource
     */
    public function getResource()
    {
        return $this->worker->getResource();
    }

    /**
     * Get capacity (number of item built per second).
     * @return float
     */
    public function getCapacity()
    {
        return $this->getCount() / $this->worker->getCycleDuration(); 
    }

    /**
     * Is this instance empty.
     */
    public function isEmpty()
    {
        return 0 == $this->getBuildingCount();
    }

    /**
     * Default constructor.
     * @param ReSettlers\Component\Worker
     */
    public function __construct(Worker $worker)
    {
        $this->worker = $worker;
    }
}
