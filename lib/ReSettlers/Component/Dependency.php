<?php

namespace ReSettlers\Component;

/**
 * Immutable dependency object.
 */
class Dependency
{
    /**
     * @var ReSettlers\Resource
     */
    protected $resource;

    /**
     * @var int
     */
    protected $count;

    /**
     * Get resource consummed.
     * @return ReSettlers\Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Get resource count per cycle
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Default constructor.
     * @param Resource $resource Consummed resource
     * @param int $count Resource count per cycle
     */
    public function __construct(Resource $resource, $count = 1)
    {
        $this->resource = $resource;
        $this->count = $count;
    }
}
