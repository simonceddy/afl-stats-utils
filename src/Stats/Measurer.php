<?php
namespace AflUtils\Stats;

use AflUtils\AflPlayer;
use AflUtils\Support\Constants;
use Ds\Map;

class Measurer
{
    protected Map $min;

    protected Map $max;

    protected Map $totals;

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

    protected function measureStats(AflPlayer $player)
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
        $games = $player->getGames();
        
        if ($games >= $this->minGames) {
            $this->measureStats($player);
        }
    }

    public function measure(iterable $data)
    {
        isset($this->min) ?: $this->min = new Map();
        isset($this->max) ?: $this->max = new Map();
        isset($this->totals) ?: $this->totals = new Map();

        foreach ($data as $player) {
            $this->loopThroughStats($player);
        }

        return [
            $this->min,
            $this->max,
        ];
    }
}
