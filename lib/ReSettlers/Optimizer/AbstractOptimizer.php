<?php

namespace ReSettlers\Optimizer;

abstract class AbstractOptimizer implements Optimizer
{
    /**
     * @var array
     */
    protected $options = array();

    public function getDefaultOptions()
    {
        return array();
    }

    public function setOptions(array $options)
    {
        $this->options = $options + $this->getDefaultOptions();
    }
}
