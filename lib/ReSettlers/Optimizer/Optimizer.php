<?php

namespace ReSettlers\Optimizer;

use ReSettlers\Profile\Profile;

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
     * Optimize worker profile.
     * @param ReSettlers\Profile\Profile $profile
     */
    public function optimize(Profile $profile);
}
