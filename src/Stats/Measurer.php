<?php
namespace AflUtils\Stats;

use AflUtils\AflPlayer;
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

    protected function compareToMinMax(AflPlayer $player)
    {
        $stats = $player->getStats();

        $currentPlayer = $player->getNames();

        $games = $player->getGames();

        foreach ($stats as $stat => $val) {
            if (!is_numeric($val)) {
                continue;
            }
            $perGame = round($val / $games, Constants::STAT_PRECISION);
            if (!isset($this->min[$stat])
                || $perGame < $this->min[$stat]['value']
            ) {
                $this->min[$stat] = new Map([
                    'player' => $currentPlayer,
                    'value' => $perGame,
                    'games' => $games
                ]);
            }
            if (!isset($this->max[$stat])
                || $perGame > $this->max[$stat]['value']
            ) {
                $this->max[$stat] = new Map([
                    'player' => $currentPlayer,
                    'value' => $perGame,
                    'games' => $games
                ]);
            }
        }
    }

    protected function loopThroughStats(AflPlayer $player)
    {
        $this->statCounter->process($player);
        
        $games = $player->getGames();

        if ($games >= $this->minGames) {
            $this->compareToMinMax($player);
        }
    }

    public function measure(iterable $data)
    {
        isset($this->min) ?: $this->min = new Map();
        isset($this->max) ?: $this->max = new Map();
        isset($this->medians) ?: $this->medians = new Medians();
        isset($this->totals) ?: $this->totals = new Map();
        isset($this->statCounter) ?: $this->statCounter = new CountStats();

        foreach ($data as $player) {
            $this->loopThroughStats($player);
        }

        [$totals, $perGame] = $this->statCounter->results();

        // TODO separate median calc
        foreach ($perGame as $stat => $amounts) {
            $this->medians->calculate($stat, $amounts);
            // dd(GetGeometricMean::from($amounts));
            $this->stats['means'][$stat] = Average::mean($amounts->toArray());
            $this->stats['medians'][$stat] = Average::median($amounts->toArray());
        }

        return [
            $this->min,
            $this->max,
            $this->medians->results(),
            $this->stats
        ];
    }
}
