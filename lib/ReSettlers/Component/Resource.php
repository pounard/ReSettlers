<?php

namespace ReSettlers\Component;

/**
 * Immutable object, this are our nodes in the Resource graph.
 */
class Resource extends Component
{
    /**
     * Resource search time, in seconds.
     * @var int
     */
    protected $searchTime = 0;

    /**
     * Deposit unit quantity.
     * @var int
     */
    protected $depositUnitCount = 0;

    /**
     * Is the resource natural and searchable using a geologist.
     * @return bool
     */
    public function isSearchable()
    {
        return (0 < $this->searchTime) && (0 < $this->depositUnitCount);
    }

    /**
     * Get search time for a geologist to find this resource.
     * @return int Search time in seconds.
     */
    public function getSearchTime()
    {
        return $this->searchTime;
    }

    /**
     * Get natural deposit unit quantity.
     * @return int
     */
    public function getDepositUnitCount()
    {
        return $this->depositUnitCount;
    }

    /**
     * Default constructor.
     * @param int|string $key
     * @param string $name Human readable name
     * @param int $searchTime
     * @param int $depositUnitCount
     */
    public function __construct($key, $name, $searchTime = 0, $depositUnitcount = 0)
    {
        parent::__construct($key, $name);
        $this->searchTime = $searchTime;
        $this->depositUnitCount = $depositUnitcount;
    }
}
