<?php
namespace AflUtils\Stats;

use AflUtils\Support\Constants;
use Ds\Map;

class PercentileCalc
{
    protected $min;

    protected $max;

    public function __construct(Map $min, Map $max)
    {
        $this->min = $min;

        $this->max = $max;
    }

    public function calculate(string $stat, float $amount)
    {
        //$min = $this->min[$stat]['value'];

        if (!isset($this->max[$stat])) {
            throw new \InvalidArgumentException(
                "Unknown stat: {$stat}"
            );
        }

        $max = $this->max[$stat]['value'];

        if ($amount <= 0) {
            return 0;
        }

        return round(($amount / $max), Constants::STAT_PRECISION);
    }

    public function __get(string $name)
    {
        if ($name === 'min') {
            return $this->min;
        }
    
        if ($name === 'max') {
            return $this->max;
        }

        throw new \Exception(
            "Undefined property: {$name}"
        );
    }
}
