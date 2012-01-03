<?php

namespace ReSettlers\Optimizer;

use ReSettlers\Profile\LeveledWorkerSet;
use ReSettlers\Profile\Profile;

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

    /**
     * Find next revelant upgrade inside the given bounds for the given
     * WorkerSet.
     * @param ReSettlers\Profile\LeveledWorkerSet $workerSet
     * @param int $max
     * @return array First value is new count, second is new building count.
     */
    protected function findNextRevelantUpgrade(LeveledWorkerSet $workerSet)
    {
        $max = $this->options['workerMaximumLevel'];

        $currentBuildingCount = $workerSet->getBuildingCount();
        $currentLevel = $workerSet->getLevel();

        $foundLevel = NULL;
        $foundBuildingCount = $currentBuildingCount;

        for ($level = $currentLevel + 1; $level <= $max; ++$level) {

            $newBuildingCount = ceil(($currentBuildingCount * $currentLevel) / ($level));

            if ($newBuildingCount < $foundBuildingCount) {
                $foundLevel = $level;
                $foundBuildingCount = $newBuildingCount;
            }
        }

        return array($foundLevel, $foundBuildingCount);
    }

    public function optimize(Profile $profile)
    {
        $remaining = $this->options['workerMaximumUpgrades'];
        $neverUpdate = $this->options['neverUpgrade'];

        do {
            $break = $remaining;

            foreach ($profile as $multiWorkerSet) {

                $worker = $multiWorkerSet->getWorker();

                foreach ($multiWorkerSet as $levelWorkerSet) {

                    $levelBuildingCount = $levelWorkerSet->getBuildingCount();
                    $level = $levelWorkerSet->getLevel();

                    if (
                        // Do not upgrade finite workers.
                        $worker->isFinite() ||
                        // No upgrade needed.
                        $levelBuildingCount == 1
                    ){
                        continue;
                    }

                    list($nextLevel, $newBuildingCount) = $this->findNextRevelantUpgrade($levelWorkerSet);

                    if (null !== $nextLevel && $levelBuildingCount <= $remaining) {
                        $multiWorkerSet->addWorkers($newBuildingCount, $nextLevel);
                        $multiWorkerSet->removeWorkers($levelBuildingCount, $level);
                        $remaining -= $levelBuildingCount;
                    }
                }
            }

            if ($break === $remaining) {
                break;
            }
        } while ($remaining);
    }
}
