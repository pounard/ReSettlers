<?php

namespace ReSettlers\Profile;

use ReSettlers\Component\Worker;

/**
 * Representation of a set of workers of the same level.
 */
class LeveledWorkerSet extends WorkerSet
{
    /**
     * Upgrading is irrevelant.
     */
    const UPGRADE_IRREVELANT = -1;

    /**
     * Number of buildings.
     * @var int
     */
    protected $count;

    /**
     * Building level.
     * @var int
     */
    protected $level = 1;

    /**
     * Add this working chain (needed it twice).
     * @param int $delta
     */
    public function increment($delta = 1)
    {
        $this->count += $delta;
    }

    /**
     * Add this working chain (needed it twice).
     * @param int $delta
     */
    public function decrement($delta = 1)
    {
        if ($delta > $this->count) {
            throw new \RuntimeException("Cannot remove more building than the instance carries");
        }

        $this->count -= $delta;
    }

    /**
     * Get current worker set worker augment level.
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    public function getCount()
    {
        return $this->count * $this->level;
    }

    public function getBuildingCount()
    {
        return $this->count;
    }

    /**
     * Default constructor.
     * @param ReSettlers\Component\Worker $worker
     * @param int $count
     */
    public function __construct(Worker $worker, $count = 1, $level = 1)
    {
        parent::__construct($worker);
        $this->count = $count;
        $this->level = $level;
    }

    public function __toString()
    {
        return $this->worker . " (" . $this->level . ")";
    }
}
