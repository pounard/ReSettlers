<?php

namespace ReSettlers;

/**
 * End-user point of interest, who builds a particular resource.
 */
class Worker
{
    /**
     * Human readable name.
     * @var string
     */
    protected $name;

    /**
     * @var ReSettlers\Resource
     */
    protected $resource;

    /**
     * Get huamn readable name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Circular dependency breaker, let the resource set itself on the worker.
     * @param ReSettlers\Resource $resource
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get which resource this worker build per cycle.
     * @return ReSettlers\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Default constructor.
     * @param string $name Human readable name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
