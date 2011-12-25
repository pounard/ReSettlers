<?php

namespace ReSettlers\Optimizer;

use ReSettlers\Component\WorkerSet;

class UpgradeOptimizer extends AbstractOptimizer
{
    public function getDefaultOptions()
    {
        return array(
            'workerMaximumLevel' => 2,
            'workerMaximumUpgrades' => 9999,
            'neverUpgrade' => array(),
        );
    }

    public function optimize(array $workerChain)
    {
        $remaining = $this->options['workerMaximumUpgrades'];
        $neverUpdate = $this->options['neverUpgrade'];
        $maximumLevel = $this->options['workerMaximumLevel'];

        $workerChain = array_filter($workerChain, function ($workerSet) use ($neverUpdate) {
            return !in_array($workerSet->getWorker()->getResource()->getKey(), $neverUpdate);
        });

        uasort($workerChain, function ($a, $b) {
            return $b->getFinalCount() - $a->getFinalCount();
        });

        do {
            $break = $remaining;

            foreach ($workerChain as $key => $workerSet) {
                $count = $workerSet->getFinalCount();

                if (
                    // No upgrade needed.
                    $count > 1 &&
                    // We have enough upgrades remaining.
                    $count <= $remaining
                ){
                    // The worker did not reach the maximum allowed upgrades.
                    $nextRevelantupgrade = $workerSet->getNextRevelantUpgrade();

                    if (WorkerSet::UPGRADE_IRREVELANT !== $nextRevelantupgrade &&
                        $nextRevelantupgrade <= $maximumLevel
                    ){
                        $workerSet->upgrade();
                        $remaining -= floor($count);
                    } else {
                        // Do not attempt this building upgrade anymore.
                        unset($workerChain[$key]);
                    }
                }
            }

            if ($break === $remaining) {
                break;
            }
        } while ($remaining);
    }
}
