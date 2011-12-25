<?php

namespace ReSettlers\Component;

/**
 * Reprensation of a full worker tree needed for building a specific resource.
 */
class WorkerSet
{
    /**
     * Upgrading is irrevelant.
     */
    const UPGRADE_IRREVELANT = -1;

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
     * Building level.
     * @var int
     */
    protected $level = 1;

    /**
     * Increase level.
     * @param int $delta Number of upgrades to do
     */
    public function upgrade($delta = 1)
    {
        $this->level += $delta;
    }

    /**
     * Downgrade level.
     * @param int $delta Number of upgrades to remove
     */
    public function downgrade($delta = 1)
    {
        $this->level -= $delta;
    }

    /**
     * Return the necessary upgrade delta for having a positive result over the
     * final count.
     * @return int
     */
    public function getNextRevelantUpgrade()
    {
        $currentCount = $this->getFinalCount();

        if (1 === $currentCount) {
            return self::UPGRADE_IRREVELANT;
        }

        $level = $this->level;

        do {
            $newCount = ceil($this->count / ++$level);
        } while ($newCount === $currentCount && 1 < $newCount);

        return $level;
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
     * @return ReSettlers\Component\Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Get current worker set worker augment level.
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Number of needed building of this kind.
     */
    public function getFinalCount()
    {
        return ceil($this->count / $this->level);
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

    public function __toString()
    {
        return $this->worker . " (" . $this->level . ")";
    }
}
