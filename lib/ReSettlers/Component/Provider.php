<?php

namespace ReSettlers\Component;

/**
 * Fetch resources.
 */
interface Provider
{
    /**
     * Get available resources keys.
     * @return array Array of keys
     */
    public function getAvailableResourceKeys();
  
    /**
     * Get available resources names.
     * @return array Key/value pairs, keys are keys values are names
     */
    public function getAvailableResourceNames();
  
    /**
     * Get resource by key.
     * @param string $key Key
     * @return ReSettlers\Component\Resource Resource with worker set
     * @throws RuntimeException If resource does not exists
     */
    public function getResourceByKey($key);

    /**
     * Get available worker keys.
     * @return array Array of keys
     */
    public function getAvailableWorkerKeys();

    /**
     * Get available worker names.
     * @return array Key/value pairs, keys are keys values are names
     */
    public function getAvailableWorkerNames();

    /**
     * Get worker by key.
     * @param string $key Key
     * @return ReSettlers\Component\Worker Resource with worker set
     * @throws RuntimeException If worker does not exists
     */
    public function getWorkerByKey($key);

    /**
     * Get all workers that can build this resource.
     * @param string|ReSettlers\Component\Resource $resource Resource or
     * resource key
     * @return array Array of Worker instances where keys are worker keys
     * @throws RuntimeException If resource does not exists or no worker found
     */
    public function getWorkersForResource($resource);
}
