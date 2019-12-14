<?php
namespace AflUtils\Stats;

use AflUtils\AflPlayer;
use AflUtils\Pipeline\Pipe;
use AflUtils\Processors\Players\PerGameCounter;
use AflUtils\Processors\Players\StatMaximums;
use AflUtils\Processors\Players\StatMinimums;
use AflUtils\Processors\Players\TotalsCounter;
use AflUtils\Support\Constants;
use AflUtils\Support\GetGeometricMean;
use AflUtils\Support\GetMedianKeys;
use Ds\Deque;
use Ds\Map;
use MathPHP\Statistics\Average;
use MathPHP\Statistics\Outlier;

/**
 * The Measurer class performs aggregating, sorting and some calculations of
 * stats.
 * 
 * @todo Get min-max from stats
 * @todo Separate totals and per game averages
 */
class Measurer
{
    protected Map $min;

    protected Map $max;

    protected Map $totals;

    protected CountStats $statCounter;

    protected Medians $medians;

    protected array $stats = [];

    /**
     * The minimum games played before a players stats are considered.
     *
     * @var int
     */
    protected $minGames;

    public function __construct(int $minGames = null)
    {
        $this->minGames = $minGames > 0 ? $minGames : Constants::MIN_GAMES;
    }

    public function measure(iterable $data)
    {
        $min = new Map();
        $max = new Map();
        $totals = new Map();
        $perGame = new Map();

        $pipe = new Pipe([
            new TotalsCounter($totals),
            new PerGameCounter($perGame),
            new StatMinimums($min),
            new StatMaximums($max),
        ]);

        foreach ($data as $player) {
            $pipe($player);
        }
        
        return [$min, $max, $totals, $perGame];
    }
}
