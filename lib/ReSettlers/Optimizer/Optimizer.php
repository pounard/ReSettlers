<?php

namespace ReSettlers\Optimizer;

interface Optimizer
{
    /**
     * Set optimizer options.
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Get defaults.
     * @return array
     */
    public function getDefaultOptions();

    /**
     * Optimize working chain.
     * @param array $workingChain Array of WorkerSet instances.
     */
    public function optimize(array $workerChain);
}
