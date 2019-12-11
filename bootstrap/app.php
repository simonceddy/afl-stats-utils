<?php

use AflUtils\AflUtilsProvider;
use Eddy\DotConfig\LoadConfigFromPaths;
use Eddy\Path\Path;
use Pimple\Container;

$app = new Container();

$app['path'] = function () {
    return new Path(dirname(__DIR__));
};

$app['config'] = function ($app) {
    return (new LoadConfigFromPaths())->load($app['path']->config);
};

$shortcuts = $app['config']->get('app.shortcuts', []);

foreach ($shortcuts as $shortcut => $path) {
    $app['path']->set($shortcut, $path);
};

$app->register(new AflUtilsProvider($app['config']));

return $app;
