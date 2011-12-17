<?php

namespace ReSettlers;

/**
 * Reprensation of a full worker tree needed for building a specific resource.
 */
class WorkerSet
{
    /**
     * Current node worker.
     * @var ReSettlers\Worker
     */
    protected $worker;

    /**
     * Worker count needed in order to satisfy dependent worker.
     * @var int
     */
    protected $count;

    /**
     * Keep the multiplication factory aside of the raw base count, for later
     * potential modification without loosing track of real consumption.
     * @var int
     */
    protected $factor = 1;

    /**
     * Multiply number of worker needed on this node.
     * @param int $factor
     */
    public function multiply($factor)
    {
        $this->factor *= $factor;
    }

    /**
     * Add this working chain (needed it twice).
     * @param int $delta
     */
    public function increment($delta = 1)
    {
        $this->count += $delta;
    }

    /**
     * Get current node worker.
     * @return ReSettlers\Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Number of needed building of this kind.
     */
    public function getFinalCount()
    {
        return $this->count * $this->factor;
    }

    /**
     * Default constructor.
     * @param ReSettlers\Worker $worker
     * @param int $count
     */
    public function __construct(Worker $worker, $count = 1)
    {
        $this->worker = $worker;
        $this->count = $count;
    }
}
