<?php

namespace ReSettlers\Resolver;

use ReSettlers\Component\Resource;
use ReSettlers\Component\Provider;
use ReSettlers\Component\ProviderAware;
use ReSettlers\Component\Worker;
use ReSettlers\Profile\Profile;

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
     * @var ReSettlers\Profile\Profile
     */
    protected $profile;

    public function setProvider(Provider $provider)
    {
        $this->profile = null;
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

        $this->profile->addWorkers($worker, $count);

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
     * @return ReSettlers\Profile\Profile
     */
    public function find()
    {
        if (!isset($this->profile)) {
            $this->profile = new Profile();
            $this->profile->setProvider($this->getProvider());

            // Ensure we wont hit a null instance during the resolve algorithm.
            $this->getProvider();

            foreach ($this->targets as $target) {
                $this->resolveDependencies($target->getResource(), $target->getCount());
            }
        }
        return $this->profile;
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
