<?php

namespace ReSettlers;

class JsonResourceProvider implements ResourceProvider
{
    /**
     * JSON decoded data.
     * @var array
     */
    protected $data;

    /**
     * Array of loaded Resources instances.
     * @var array
     */
    protected $resources;

    public function getAvailableResourceKeys()
    {
        return array_keys($this->data);
    }
  
    public function getAvailableResourceNames()
    {
        $ret = array();

        foreach ($this->getAvailableResourceKeys() as $key) {
            $ret[$key] = $this->getResourceByKey($key)->getName();
        }

        return $ret;
    }

    public function getResourceByKey($key)
    {
        if (!isset($this->resources[$key])) {
            if (isset($this->data[$key])) {

                $resdata = $this->data[$key];
                $dependencies  = array();

                if (isset($resdata['dependencies'])) {
                    foreach ($resdata['dependencies'] as $_key => $count) {
                        $dependencies[] = new Dependency($this->getResourceByKey($_key), $count);
                    }
                }

                if (!isset($resdata['name']) || !is_string($resdata['name'])) {
                    throw new \RuntimeException("Resource $key has no human readable name or name is not a valid string.");
                }

                if (!isset($resdata['time']) || !is_int($resdata['time'])) {
                    throw new \RuntimeException("Resource $key has no time set, or time is not a valid integer.");
                }

                if (!isset($resdata['worker']) || !is_string($resdata['worker'])) {
                    throw new \RuntimeException("Resource $key has no worker set, or worker is not a valid string.");
                }

                $this->resources[$key] = new Resource($key, $resdata['name'], $resdata['time'], new Worker($resdata['worker']), $dependencies);
            } else {
                throw new \RuntimeException("Resource $key does not exists");
            }
        }

        return $this->resources[$key];
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
