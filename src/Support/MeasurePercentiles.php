<?php
namespace AflUtils\Support;

use AflUtils\Stats\PercentileCalc;

class MeasurePercentiles
{
    /**
     * The PercentileCalc instance
     * 
     * @var PercentileCalc
     */
    protected $calc;

    public function __construct(PercentileCalc $calc)
    {
        $this->calc = $calc;
    }

    public function process(float $value, $stat)
    {
        if ($stat !== 'number'
            && $stat !== 'average_disposals'
            && $stat !== 'time_on_ground'
        ) {
            return $this->calc->calculate($stat, $value);
        }
        return null;
    }

    public function __invoke($value, $name)
    {
        return $this->process($value, $name);
    }
}
