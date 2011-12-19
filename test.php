<?php

use ReSettlers\Optimizer\UpgradeOptimizer;
use ReSettlers\JsonResourceProvider;
use ReSettlers\Dependency;
use ReSettlers\Resolver;
use ReSettlers\ResolverOption;
use ReSettlers\Resource;
use ReSettlers\Worker;
use ReSettlers\WorkerSet;

// Register basic autoloader.
spl_autoload_register(function ($className) {
    $parts = explode('\\', $className);
    if ('ReSettlers' === $parts[0]) {
        $filename = __DIR__ . '/lib/' . implode('/', $parts) . '.php';
        if (file_exists($filename)) {
            require_once $filename;
            return TRUE;
        }
    }
    return FALSE;
});

$provider = new JsonResourceProvider(__DIR__ . '/resources/resources.json');

$resolver = new Resolver(array(
    new ResolverOption($provider->getResourceByKey('arc'), 1),
    new ResolverOption($provider->getResourceByKey('epeefer'), 1),
    new ResolverOption($provider->getResourceByKey('outil'), 2),
    new ResolverOption($provider->getResourceByKey('epeebronze'), 2),
    //new ResolverOption($provider->getResourceByKey('biere'), 2),
    //new ResolverOption($provider->getResourceByKey('pain'), 2),
));
$optimizer = new UpgradeOptimizer();
$optimizer->setOptions(array(
    'workerMaximumLevel' => 3,
));
$chain = $resolver->find();
$optimizer->optimize($chain);

foreach ($chain as $workerSet) {
    print " - " . $workerSet->getFinalCount() . " x " . $workerSet . "\n";
}
