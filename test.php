<?php

use ReSettlers\Component\Dependency;
use ReSettlers\Component\JsonProvider;
use ReSettlers\Component\Resource;
use ReSettlers\Component\Worker;
use ReSettlers\Component\WorkerSet;
use ReSettlers\Optimizer\UpgradeOptimizer;
use ReSettlers\Resolver\Resolver;
use ReSettlers\Resolver\Target;

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

$provider = new JsonProvider(__DIR__ . '/resources/resources.json');

/*
 * Sapin/Houille/Arc SaucisseVolante
 *
$resolver = new Resolver(array(
    new Target($provider->getResourceByKey('arc'), 2),
    new Target($provider->getResourceByKey('planchesapin'), 2),
    new Target($provider->getResourceByKey('houille'), 2),
));
 */

/*
 * Sapin/Houille/Arc PonchoVire
 *
$resolver = new Resolver(array(
    new Target($provider->getResourceByKey('arc'), 2),
    new Target($provider->getResourceByKey('planchesapin'), 2),
    new Target($provider->getResourceByKey('houille'), 10),
));
 */

/*
 * Pain/Vin/Cheval PonchoVire
 *
$resolver = new Resolver(array(
    new Target($provider->getResourceByKey('pain'), 2),
    new Target($provider->getResourceByKey('biere'), 2),
    new Target($provider->getResourceByKey('cheval'), 1),
));
 */

$resolver = new Resolver(array(
    //new Target($provider->getResourceByKey('epeefer'), 1),
    //new Target($provider->getResourceByKey('outil'), 2),
    //new Target($provider->getResourceByKey('epeebronze'), 2),
    //new Target($provider->getResourceByKey('planchesapin'), 2),
    new Target($provider->getResourceByKey('biere'), 4),
    new Target($provider->getResourceByKey('pain'), 2),
    new Target($provider->getResourceByKey('cheval'), 1),
    //new Target($provider->getResourceByKey('planchefeuillu'), 5),
    //new Target($provider->getResourceByKey('houille'), 10),
    //new Target($provider->getResourceByKey('arc'), 1),
));

$resolver->setProvider($provider);
$optimizer = new UpgradeOptimizer();
$optimizer->setOptions(array(
    'workerMaximumLevel' => 3,
));
$chain = $resolver->find();
$optimizer->optimize($chain);

foreach ($chain as $workerSet) {
    print " - " . $workerSet->getFinalCount() . " x " . $workerSet . "\n";
}

