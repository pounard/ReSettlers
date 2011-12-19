<?php

namespace ReSettlers;

/**
 * Immutable object, this are our nodes in the Resource graph.
 */
class Resource
{
    /**
     * Internal identifier. This key is dependent on provider.
     * @var int|string
     */
    protected $key;

    /**
     * Human readable name.
     * @var name
     */
    protected $name;

    /**
     * @var array
     */
    protected $dependencies;

    /**
     * Build time for 1 unit in minutes.
     * @var int
     */
    protected $time;

    /**
     * Worker that builds this ressource.
     * @var ReSettlers\Worker
     */
    protected $worker;

    /**
     * Get human readable name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get time per cycle, in seconds.
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get consummed dependencies per cycle.
     * @return array Array of ReSettlers\Dependency
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Get worker who build one unit of this resource per cycle.
     * @return ReSettlers\Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Get unique identifier for this resource.
     * @return int|string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Default constructor.
     * @param int|string $key Internal identifier
     * @param string $name Human readable name
     * @param int $time Time per cycle, in seconds
     * @param ReSettlers\Worker $worker Worker that build this resource
     * @param array $dependencies Array of ReSettlers\Dependency
     */
    public function __construct($key, $name, $time, Worker $worker, array $dependencies = array())
    {
        $this->key = $key;
        $this->name = $name;
        $this->time = $time;
        $this->worker = $worker;
        $this->worker->setResource($this);
        $this->dependencies = $dependencies;
    }

    public function __toString()
    {
        return $this->name;
    }
}
