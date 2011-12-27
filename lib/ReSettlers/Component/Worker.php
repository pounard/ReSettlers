<?php

namespace ReSettlers\Component;

/**
 * End-user point of interest, who builds a particular resource.
 */
class Worker extends Component
{
    /**
     * @var ReSettlers\Resource
     */
    protected $resource;

    /**
     * @var int
     */
    protected $cycleDuration;

    /**
     * @var array
     */
    protected $dependencies;

    /**
     * @var bool
     */
    protected $finite = false;

    /**
     * Get which resource this worker build per cycle.
     * @return ReSettlers\Component\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Get cycle duration in seconds.
     * @return int
     */
    public function getCycleDuration()
    {
        return $this->cycleDuration;
    }

    /**
     * Get consummed dependencies per cycle.
     * @return array Array of ReSettlers\Component\Dependency
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Tell if the current worker will have to be rebuilt once resource has
     * been emptied.
     * @return bool
     */
    public function isFinite()
    {
        return $this->finite;
    }

    /**
     * Default constructor.
     * @param int|string $key
     * @param string $name
     * @param Resource $resource
     * @param int $cycleDuration Cycle duration in seconds
     * @param bool $finite Tell if the current worker needs to be rebuilt once
     * resource has been emptied.
     */
    public function __construct($key, $name, Resource $resource, $cycleDuration, array $dependencies = array(), $finite = false)
    {
        parent::__construct($key, $name);
        $this->resource = $resource;
        $this->cycleDuration = $cycleDuration;
        $this->dependencies = $dependencies;
        $this->finite = $finite;
    }
}
