<?php

namespace ReSettlers;

class ResolverOption
{
    /**
     * Resource to build.
     * @var ReSettlers\Resource
     */
    protected $resource;

    /**
     * Resource count to build.
     * @var int
     */
    protected $count;

    /**
     * Get resource.
     * @return ReSettlers\Resource
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * Get count.
     * @return int
     */
    public function getCount() {
        return $this->count;
    }

    public function __construct(Resource $resource, $count = 1) {
        $this->resource = $resource;
        $this->count = $count;
    }
}
