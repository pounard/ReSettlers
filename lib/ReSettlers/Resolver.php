<?php

namespace ReSettlers;

/**
 * Given a single resource, resolve the time for build one unit and the best
 * configuration possible.
 */
class Resolver
{
    /**
     * Round buildings count.
     */
    const COUNT_ROUND = 0;

    /**
     * Find common denominator and raise building number.
     */
    const COUNT_DENOMINATOR = 1;

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
     * Adjust building number mode.
     * @var int
     */
    protected $adjustMode = self::COUNT_ROUND;

    /**
     * @var float
     */
    protected $adjustThreshold = 0.2;

    /**
     * Resources for which we will never trigger a worker upgrade during
     * optimization. Mainly for edges.
     * @var array
     */
    protected $neverUpgrade = array();

    /**
     * Resolve dependencies of a single resource.
     * @param ReSettlers\Resource
     * @return ReSettlers\WorkerChain
     */
    protected function resolveDependencies(Resource $resource, $count = 1)
    {
        $worker = $resource->getWorker();
        $resKey = $resource->getKey();

        if (isset($this->workerChain[$resKey])) {
            $this->workerChain[$resKey]->increment($count);
        } else {
            $this->workerChain[$resKey] = new WorkerSet($worker, $count);
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
    public function find()
    {
        if (!isset($this->workerChain)) {
            $this->workerChain = array();

            foreach ($this->targets as $resourceOption) {
                $resource = $resourceOption->getResource();
                $this->resolveDependencies($resource, $resourceOption->getCount());
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
        $this->workerMaximumLevel = isset($options['workerMaximumLevel']) ? $options['workerMaximumLevel'] : 1;
        $this->workerUpgrades = isset($options['workerUpgrades']) ? $options['workerUpgrades'] : 0;
        $this->adjustMode = isset($options['adjustMode']) ? $options['adjustMode'] : self::COUNT_ROUND;
        $this->adjustThreshold = isset($options['adjustThreshold']) ? $options['adjustThreshold'] : 0.2;
    }

    /**
     * Default constructor. 
     * @param array $targets Array of ReSettlers\ResolverOptions instances
     */
    public function __construct(array $targets)
    {
        $this->targets = $targets;
    }
}
