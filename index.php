<?php

use AflUtils\CalculatePlayerMetrics;

require 'vendor/autoload.php';

$app = include_once 'bootstrap/app.php';

$season = 2018;

$data = json_decode(file_get_contents($app['path']->data . '/' . $season . '.json'), true);
$results = (new CalculatePlayerMetrics())->fromSeason($data);

dd($results['percentiles'][mt_rand(0, ($results['percentiles']->count() - 1))]);
// dd(count($results['percentiles']));

/* file_put_contents(
    'storage/' . $season . 'perc.json',
    json_encode($results['percentiles'], JSON_PRETTY_PRINT)
);
file_put_contents(
    'storage/' . $season . 'min.json',
    json_encode($results['min'], JSON_PRETTY_PRINT)
);
file_put_contents(
    'storage/' . $season . 'max.json',
    json_encode($results['max'], JSON_PRETTY_PRINT)
);
file_put_contents(
    'storage/' . $season . 'players.json',
    json_encode($results['players'], JSON_PRETTY_PRINT)
); */
