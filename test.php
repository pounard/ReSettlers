<?php

use ReSettlers\JsonResourceProvider;
use ReSettlers\Dependency;
use ReSettlers\Resolver;
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

$count = 1;
$resource = $provider->getResourceByKey('fer');

$resolver = new Resolver($resource, $count);
$chain = $resolver->find();
print "Pour faire $count $resource avez besoin de:\n";
print "De " . $resource->getTime() . " secondes et de\n";
foreach ($chain as $workerSet) {
    print " - " . $workerSet->getFinalCount() . " x " . $workerSet->getWorker() . "\n";
}
