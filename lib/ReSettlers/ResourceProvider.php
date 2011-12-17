<?php

namespace ReSettlers;

/**
 * Fetch resources.
 */
interface ResourceProvider
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
     * @return ReSettlers\Resource Resource with worker set
     * @throws RuntimeException If resource does not exists
     */
    public function getResourceByKey($key);
}
