<?php

use AflUtils\CalculatePlayerMetrics;
use AflUtils\Stats\Measurer;
use AflUtils\Stats\PercentileCalc;
use AflUtils\Support\GetPlayersFromSeasonData;
use AflUtils\Utils\GeneratorFactory;

require 'vendor/autoload.php';

$app = include_once 'bootstrap/app.php';

$season = 2018;

$data = json_decode(file_get_contents($app['path']->data . '/' . $season . '.json'), true);
$results = (new CalculatePlayerMetrics())->fromSeason($data);

// dd(count($results['percentiles']));

file_put_contents(
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
);
