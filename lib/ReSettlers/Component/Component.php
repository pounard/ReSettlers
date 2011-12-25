<?php

namespace ReSettlers\Component;

/**
 * Named game component common code.
 */
abstract class Component
{
    /**
     * @var int|string
     */
    protected $key;

    /**
     * Human readable name.
     * @var string
     */
    protected $name;

    /**
     * Get huamn readable name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get key.
     * @return int|string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Default constructor.
     * @param int|string $key
     * @param string $name Human readable name
     */
    public function __construct($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * Get human readable name.
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
