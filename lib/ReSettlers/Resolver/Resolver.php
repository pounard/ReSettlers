<?php

namespace ReSettlers\Resolver;

use ReSettlers\Component\Resource;
use ReSettlers\Component\Provider;
use ReSettlers\Component\ProviderAware;
use ReSettlers\Component\Worker;
use ReSettlers\Component\WorkerSet;
use ReSettlers\Component\WorkerCollection;

/**
 * Given a single resource, resolve the time for build one unit and the best
 * configuration possible.
 */
class Resolver implements ProviderAware
{
    /**
     * @var ReSettlers\Component\Provider
     */
    protected $provider;

    /**
     * Needed count of resource.
     * @var int
     */
    protected $count;

    /**
     * @var array Array of ReSettlers\Component\WorkerSet
     */
    protected $workerChain;

    /**
     * Resources for which we will never trigger a worker upgrade during
     * optimization. Mainly for edges.
     * @var array
     */
    protected $neverUpgrade = array();

    public function setProvider(Provider $provider) {
        $this->provider = $provider;
    }

    public function getProvider() {
        if (isset($this->provider)) {
            return $this->provider;
        } else {
            throw new \RuntimeException("No provider set");
        }
    }

    /**
     * Resolve dependencies of a single resource.
     * @param ReSettlers\Component\Resource
     */
    protected function resolveDependencies(Resource $resource, $count = 1)
    {
        $resKey = $resource->getKey();
        // FIXME: Find a better worker lookup algorithm.
        $worker = array_shift($this->provider->getWorkersForResource($resource));

        if (!$worker instanceof Worker) {
            throw new \RuntimeException("No worker found for resource " . $resource->getKey());
        }

        if (isset($this->workerChain[$resKey])) {
            $this->workerChain[$resKey]->increment($count);
        } else {
            $this->workerChain[$resKey] = new WorkerSet($worker, $count);
        }

        foreach ($worker->getDependencies() as $dependency) {
            $neededResource = $dependency->getResource();
            // The heart of this algorithm is a simple cross multiplication. No
            // complexity lies in here.
            $neededWorkers = ($worker->getCycleDuration() / $worker->getCycleDuration()) * $dependency->getCount() * $count;
            $this->resolveDependencies($neededResource, $neededWorkers);
        }
    }

    /**
     * Find the full ressource chain needed to build this resource.
     * @return ReSettlers\WorkerChain
     */
    public function find()
    {
        if (!isset($this->workerChain)) {
            $this->workerChain = array();

            // Ensure we wont hit a null instance during the resolve algorithm.
            $this->getProvider();

            foreach ($this->targets as $target) {
                $this->resolveDependencies($target->getResource(), $target->getCount());
            }
        }
        return $this->workerChain;
    }

    /**
     * Set options.
     * @param array $options Set of options for computation.
     */
    public function setOptions(array $options)
    {
        $this->workerChain = null;
    }

    /**
     * Default constructor. 
     * @param array $targets Array of ReSettlers\Resolver\Target instances
     */
    public function __construct(array $targets)
    {
        $this->targets = $targets;
    }
}
