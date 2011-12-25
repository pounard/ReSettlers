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
     * Default constructor.
     * @param int|string $key
     * @param string $name
     * @param Resource $resource
     * @param int $cycleDuration Cycle duration in seconds
     */
    public function __construct($key, $name, Resource $resource, $cycleDuration, array $dependencies = array())
    {
        parent::__construct($key, $name);
        $this->resource = $resource;
        $this->cycleDuration = $cycleDuration;
        $this->dependencies = $dependencies;
    }
}
