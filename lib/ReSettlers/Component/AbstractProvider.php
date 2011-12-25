<?php

namespace ReSettlers\Component;

/**
 * Non optimized component provider base implementation. Fits well with any
 * implementation that works fully in memory.
 */
abstract class AbstractProvider implements Provider
{
    /**
     * Array of loaded Resource instances.
     * @var array
     */
    protected $resources;

    /**
     * Array of loaded Worker instances
     * @var array
     */
    protected $workers;

    public function getAvailableResourceNames()
    {
        $ret = array();

        foreach ($this->getAvailableResourceKeys() as $key) {
            $ret[$key] = $this->getResourceByKey($key)->getName();
        }

        return $ret;
    }

    public function getAvailableWorkerNames()
    {
        $ret = array();

        foreach ($this->getAvailableWorkerKeys() as $key) {
            $ret[$key] = $this->getWorkerByKey($key)->getName();
        }

        return $ret;
    }

    public function getWorkersForResource($resource)
    {
        $ret = array();

        if ($resource instanceof Resource) {
            $resourceKey = $resource->getKey();
        } else {
            $resourceKey = $resource;
        }

        foreach ($this->getAvailableWorkerKeys() as $workerKey) {
            $worker = $this->getWorkerByKey($workerKey);

            if ($resourceKey === $worker->getResource()->getKey()) {
                $ret[$workerKey] = $worker;
            }
        }

        return $ret;
    }
}
