<?php

namespace ReSettlers\Component;

interface ProviderAware
{
    /**
     * Set component provider.
     * @param ReSettlers\Component\Provider $provider
     */
    public function setProvider(Provider $provider);

    /**
     * Get component provider.
     * @return ReSettlers\Component\Provider
     */
    public function getProvider();
}
