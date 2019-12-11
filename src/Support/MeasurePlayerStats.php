<?php
namespace AflUtils\Support;

use AflUtils\AflPlayer;

class MeasurePlayerStats
{
    protected $perStat;

    public function __construct(MeasurePercentiles $perStat)
    {
        $this->perStat = $perStat;
    }

    public function process(AflPlayer $player)
    {
        // dd($player);
        $stats = $player->getStats();
        $games = $player->getGames();
        
        $ratings = [];
        
        foreach ($stats as $stat => $val) {
            if (!is_numeric($val)) {
                continue;
            }
            $result = $this->perStat->process($val / $games, $stat);
            $result === null ?: $ratings[$stat] = $result;
        }

        return [
            $player->getNames(),
            $ratings,
            $games
        ];
    }

    public function __invoke(AflPlayer $player)
    {
        return $this->process($player);
    }
}
