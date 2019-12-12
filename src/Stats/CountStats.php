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
    protected Map $stats;

    public function __construct()
    {
        $this->stats = new Map();
    }

    protected function sortStats()
    {
        foreach ($this->stats as $stat => $amounts) {
            $amounts->sort();
        }
    }

    public function process(AflPlayer $player)
    {
        $stats = $player->getStats();

        foreach ($stats as $stat => $value) {
            isset($this->stats[$stat]) ?: $this->stats[$stat] = new Deque();
            $this->stats[$stat]->push($value);
        }
    }

    public function results()
    {
        $this->sortStats();

        return $this->stats;
    }
}
