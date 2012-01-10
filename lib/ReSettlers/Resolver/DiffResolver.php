<?php

namespace ReSettlers\Resolver;

use ReSettlers\Component\Resource;
use ReSettlers\Component\Worker;
use ReSettlers\Profile\Profile;

/**
 * Resolver that computes the missing profile addition given an original
 * existing production profile.
 */
class DiffResolver extends Resolver
{
    /**
     * @var ReSettlers\Profile\Profile
     */
    protected $originalProfile;

    /**
     * Get original profile.
     * @return ReSettlers\Profile\Profile
     */
    public function getOriginalProfile()
    {
        return $this->originalProfile;
    }

    protected function resolveDependencies(Resource $resource, $count = 1, Worker $worker = null)
    {
        if (isset($worker)) {
            if ($worker->getResource() !== $resource) {
                throw new \RuntimeException("Worker $worker can not build $resource");
            }
        } else {
            $worker = $this->getBestWorkerForResource($resource);
        }

        $cc = $this->originalProfile->getNetCapacity($resource);
        $cn = $count / $worker->getCycleDuration(); // Needed capacity.

        if ($cc > $cn) {
            // The profile already holds the needed capacity for this to work.
            return;
        }

        $t = $worker->getCycleDuration();

        // Don't ask me where it comes from, my mathematics are far from now
        // and I scratched a few sheets of paper in order to get to it.
        $n = 0 - ((($cc * $t) - $count) / ($t * $t));

        return parent::resolveDependencies($resource, $n, $worker);
    }

    /**
     * Default constructor. 
     * @param array $targets Array of ReSettlers\Resolver\Target instances
     * @param ReSettlers\Profile\Profile $profile Original profile
     */
    public function __construct(array $targets, Profile $profile)
    {
        $this->targets = $targets;
    }
}
