<?php

namespace ReSettlers;

/**
 * Given a single resource, resolve the time for build one unit and the best
 * configuration possible.
 */
class Resolver
{
    /**
     * Multiplication factor for linearizing resources consumption in order to
     * get an integer number of buildings.
     * @var int
     */
    protected $factor;

    /**
     * Resource you want to build.
     * @var ReSettlers\Resource
     */
    protected $targettedResource;

    /**
     * Needed count of resource.
     * @var int
     */
    protected $count;

    /**
     * @var ReSettlers\WorkerChain
     */
    protected $workerChain;

    /**
     * Resolve dependencies of a single resource.
     * @param ReSettlers\Resource
     * @return ReSettlers\WorkerChain
     */
    protected function resolveDependencies(Resource $resource, $count = 1) {
        $worker = $resource->getWorker();
        $workerHash = spl_object_hash($worker);

        if (isset($this->workerChain[$workerHash])) {
            $this->workerChain[$workerHash]->increment($count);
        } else {
            $this->workerChain[$workerHash] = new WorkerSet($worker, $count);
        }

        foreach ($resource->getDependencies() as $dependency) {
            $neededResource = $dependency->getResource();
            $neededWorkers = ($neededResource->getTime() / $resource->getTime()) * $dependency->getCount() * $count;
            $this->resolveDependencies($neededResource, $neededWorkers);
        }
    }

    /**
     * Find the full ressource chain needed to build this resource.
     * @return ReSettlers\WorkerChain
     */
    public function find() {
        if (!isset($this->workerChain)) {
            $this->workerChain = array();
            $this->resolveDependencies($this->targettedResource, $this->count);
        }
        return $this->workerChain;
    }

    /**
     * Default constructor.
     * @param ReSettlers\Resource $targettedResource Resource you want to build
     * @param int $count
     * @param float $threshold Difference threshold that triggers the common
     * denominator computation
     */
    public function __construct(Resource $targettedResource, $count = 1, $threshold = 0.15) {
        $this->targettedResource = $targettedResource;
        $this->count = $count;
    }
}
