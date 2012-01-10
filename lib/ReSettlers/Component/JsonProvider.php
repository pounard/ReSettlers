<?php

namespace ReSettlers\Component;

class JsonProvider extends AbstractProvider
{
    /**
     * JSON decoded data.
     * @var array
     */
    protected $data;

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

    public function getAvailableResourceKeys()
    {
        return array_keys($this->data['resources']);
    }

    public function getResourceByKey($key)
    {
        if (!isset($this->resources[$key])) {
            if (isset($this->data['resources'][$key])) {

                $resdata = $this->data['resources'][$key];

                if (!isset($resdata['name']) || !is_string($resdata['name'])) {
                    throw new \RuntimeException("Resource $key has no human readable name or name is not a valid string.");
                }

                if (isset($resdata['searchtime'])) {
                    if (is_int($resdata['searchtime'])) {
                        $searchTime = $resdata['searchtime'];
                    } else {
                        throw new \RuntimeException("Search time must be an int");
                    }
                } else {
                    $searchTime = 0;
                }

                if (isset($resdata['quantity'])) {
                    if (is_int($resdata['quantity'])) {
                        $depositUnitCount = $resdata['quantity'];
                    } else {
                        throw new \RuntimeException("Deposit quantity must be an int");
                    }
                } else {
                    $depositUnitCount = 0;
                }

                $this->resources[$key] = new Resource($key, $resdata['name'], $searchTime, $depositUnitCount);
            } else {
                throw new \RuntimeException("Resource $key does not exists");
            }
        }

        return $this->resources[$key];
    }

    public function getAvailableWorkerKeys()
    {
        return array_keys($this->data['workers']);
    }

    public function getWorkerByKey($key)
    {
        if (!isset($this->workers[$key])) {
            if (isset($this->data['workers'][$key])) {

                $resdata = $this->data['workers'][$key];
                $dependencies  = array();

                try {
                    if (isset($resdata['dependencies'])) {
                        foreach ($resdata['dependencies'] as $resourceKey => $count) {
                            $dependencies[] = new Dependency($this->getResourceByKey($resourceKey), $count);
                        }
                    }
                } catch (\RuntimeException $e) {
                    throw new \RuntimeException("Invalid resource dependencies for worker $key", 0, $e);
                }

                if (!isset($resdata['name']) || !is_string($resdata['name'])) {
                    throw new \RuntimeException("Worker $key has no human readable name or name is not a valid string.");
                }

                if (!isset($resdata['time']) || !is_int($resdata['time'])) {
                    throw new \RuntimeException("Worker $key has no cycle duration set or cycle duration is not a valid integer.");
                }

                if (!isset($resdata['builds']) || !is_string($resdata['builds'])) {
                    throw new \RuntimeException("Worker $key has no built resource set or built resource is not a valid string.");
                }

                if (isset($resdata['finite'])) {
                    $finite = (bool)$resdata['finite'];
                } else {
                    $finite = false;
                }

                $this->workers[$key] = new Worker($key, $resdata['name'], $this->getResourceByKey($resdata['builds']), $resdata['time'], $dependencies, $finite);
            } else {
                throw new \RuntimeException("Worker $key does not exists");
            }
        }

        return $this->workers[$key];
    }

    /**
     * Default constructor.
     * @param string $filename JSON file path
     */
    public function __construct($filename)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            throw new \RuntimeException("$filename is not readable");
        }

        $this->data = json_decode(file_get_contents($filename), TRUE);

        if (null === $this->data) {
            throw new \RuntimeException("Invalid JSON data");
        }
    }
}
