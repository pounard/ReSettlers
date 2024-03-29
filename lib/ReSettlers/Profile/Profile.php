<?php

namespace ReSettlers\Profile;

use ReSettlers\Component\Provider;
use ReSettlers\Component\ProviderAware;
use ReSettlers\Component\Resource;
use ReSettlers\Component\Worker;

/**
 * A profile is a WorkerSet container.
 * It can iterate over the WorkerSet that it carries.
 */
class Profile implements ProviderAware, \IteratorAggregate, \Serializable
{
    /**
     * Current worker sets, keyed by worker key then by level.
     * @var array
     */
    protected $workers = array();

    /**
     * @var ReSettlers\Component\Provider
     */
    protected $provider;

    /**
     * Internal built resources cache.
     * @var array
     */
    protected $builtResourcesCache;

    /**
     * Clear internal object cache.
     */
    protected function clearCaches()
    {
        $this->builtResourcesCache = NULL;
    }

    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
    }

    public function getProvider()
    {
        if (isset($this->provider)) {
            return $this->provider;
        } else {
            throw new \RuntimeException("No provider set");
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->workers);
    }

    /**
     * Add workers.
     * @param ReSettlers\Component\Worker $worker
     * @param int $count Number of workers
     * @param int $level Level of workers
     */
    public function addWorkers(Worker $worker, $count = 1, $level = 1)
    {
        $key = $worker->getKey();

        if (!isset($this->workers[$key])) {
            $this->workers[$key] = new MultiLevelWorkerSet($worker);
        }

        $this->workers[$key]->addWorkers($count, $level);
    }

    /**
     * Remove workers.
     * @param ReSettlers\Component\Worker $worker
     * @param int $count Number of workers
     * @param int $level Level of workers
     */
    public function removeWorkers(Worker $worker, $count = 1, $level = 1)
    {
        $key = $worker->getKey();

        if (!isset($this->workers[$key])) {
            throw new \RuntimeException("Cannot remove workers that does not exist in the profile");
        }

        $this->workers[$key]->removeWorkers($count, $level);

        if ($this->workers[$key]->isEmpty) {
            unset($this->workers[$key]);
        }
    }

    /**
     * Get capacity for given resource.
     * @param ReSettlers\Component\Resource $resource
     * @return float
     */
    public function getCapacity(Resource $resource)
    {
        $ret = 0;

        foreach ($this->workers as $multiLevelWorker) {
            if ($resource === $multiLevelWorker->getResource()) {
                $ret += $multiLevelWorker->getCapacity();
            }
        }

        return $ret;
    }

    /**
     * Get capacity for the given resource after consumption.
     * @param ReSettlers\Component\Resource $resource
     * @return float Can be negative
     */
    public function getNetCapacity(Resource $resource)
    {
        return $this->getCapacity($resource) - $this->getConsumption($resource);
    }

    /**
     * Get consumption of a specific resource (number of units per second).
     * @param ReSettlers\Component\Resource $resource
     */
    public function getConsumption(Resource $resource)
    {
        $consumption = 0;

        foreach ($this->workers as $multiLevelWorker) {
            $consumption += $multiLevelWorker->getConsumption($resource);
        }

        return $consumption;
    }

    /**
     * Get a compilation of all built resources.
     * @return array Array of ReSettlers\Component\Resource instances.
     */
    public function getBuiltResources()
    {
        $ret = array();
        $provider = $this->getProvider();

        foreach ($this->workers as $workerKey => $details) {
            $resource = $provider->getWorkerByKey($workerKey)->getResource();
            $ret[$resource->getKey()] = $resource;
        }

        return $ret;
    }

    /**
     * Get total worker count.
     * @return int
     */
    public function getBuildingCount()
    {
        $ret = 0;

        foreach ($this->workers as $workerKey => $details) {
            foreach ($details as $level => $workerSet) {
                $ret += $workerSet->getBuildingCount();
            }
        }

        return $ret;
    }

    public function serialize()
    {
        throw new \Exception("Profiles can not be serialized");
    }

    public function unserialize($serialized)
    {
        throw new \Exception("Profiles can not be unserialized");
    }
}
