<?php
namespace AflUtils\Stats;

use AflUtils\AflPlayer;
use Ds\Deque;
use Ds\Map;

class CountStats
{
    /**
     * @var Deque[]
     */
    protected Map $totals;

    /**
     * @var Deque[]
     */
    protected Map $perGameTotals;

    protected array $results = [];

    public function __construct()
    {
        $this->totals = new Map();
        $this->perGameTotals = new Map();
    }

    protected function sortStats()
    {
        foreach ($this->totals as $stat => $amounts) {
            $amounts->sort();
        }
    }

    public function process(AflPlayer $player)
    {
        $stats = $player->getStats();

        $games = $player->getGames();

        foreach ($stats as $stat => $value) {
            if ($value === 0) {
                continue;
            }
            isset($this->totals[$stat]) ?: $this->totals[$stat] = new Deque();
            isset($this->perGameTotals[$stat]) ?: $this->perGameTotals[$stat] = new Deque();
            $this->totals[$stat]->push($value);
            $this->perGameTotals[$stat]->push($value / $games);
        }
    }

    /**
     * Get the sorted results.
     *
     * @return array[Map] Returns two Maps in order of season totals and per game totals.
     */
    public function results()
    {
        $this->sortStats();

        return [$this->totals, $this->perGameTotals];
    }
}
