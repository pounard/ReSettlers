<?php

namespace ReSettlers\Profile;

class MultiLevelWorkerSet extends WorkerSet implements \IteratorAggregate
{
    protected $sets = array();

    public function getIterator()
    {
        ksort($this->sets);
        return new \ArrayIterator($this->sets);
    }

    public function getBuildingCount()
    {
        $ret = 0;

        foreach ($this->sets as $workerSet) {
            $ret += $workerSet->getBuildingCount();
        }

        return $ret;
    }

    public function getCount()
    {
        $ret = 0;

        foreach ($this->sets as $workerSet) {
            $ret += $workerSet->getCount();
        }

        return $ret;
    }

    /**
     * Tells if this instance carries a LeveledWorkerSet with the given level.
     * @param int $level
     * @return bool
     */
    public function hasLevel($level)
    {
        return isset($this->sets[$level]);
    }

    /**
     * Get level worker set at the given level.
     * @param int $level
     * @return ReSettlers\Profile\LeveledWorkerSet
     * @throws RuntimeException
     */
    public function getLevelWorkerSet()
    {
        if (!isset($this->sets[$level])) {
            throw new \RuntimeException("Instance does not carry a leveled worker set for the level $level");
        }

        return $this->sets[$level];
    }

    /**
     * Add workers.
     * @param ReSettlers\Component\Worker $worker
     * @param int $count Number of workers
     * @param int $level Level of workers
     */
    public function addWorkers($count = 1, $level = 1)
    {
        if (!isset($this->sets[$level])) {
            $this->sets[$level] = new LeveledWorkerSet($this->worker, $count, $level);
        } else {
            $this->sets[$level]->increment($count);
        }
    }

    /**
     * Remove workers.
     * @param ReSettlers\Component\Worker $worker
     * @param int $count Number of workers
     * @param int $level Level of workers
     */
    public function removeWorkers($count = 1, $level = 1)
    {
        if (!isset($this->sets[$level])) {
            throw new \RuntimeException("Cannot remove non existing workers at level $level");
        }

        $this->sets[$level]->decrement($count);

        if ($this->sets[$level]->isEmpty()) {
            unset($this->sets[$level]);
        }
    }
}
