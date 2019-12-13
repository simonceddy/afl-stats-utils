<?php
namespace AflUtils\Stats;

use AflUtils\Support\GetMedianKeys;
use Ds\Map;

class Medians
{
    protected Map $medians;

    public function __construct()
    {
        $this->medians = new Map();
    }

    public function calculate(string $name, iterable $amount)
    {
        $key = GetMedianKeys::from($amount);

        if (is_array($key)) {
            [$key1, $key2] = $key;
            $amount1 = $amount[$key1];
            $amount2 = $amount[$key2];
            $this->medians[$name] = ($amount1 + $amount2) / 2;
        } else {
            $this->medians[$name] = $amount[$key];
        }
    }

    public function results()
    {
        return $this->medians;
    }
}
