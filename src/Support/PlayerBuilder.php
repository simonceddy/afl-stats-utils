<?php
namespace AflUtils\Support;

use AflUtils\AflPlayer;
use Ds\Map;

class PlayerBuilder
{
    public static function makeFrom(array $player)
    {
        $games = isset($player['games']) ? $player['games'] : 0;
    
        $playerObject = new AflPlayer(
            $player['player'],
            $player['number'],
            $games,
            new Map(array_filter($player, function ($stat) {
                return $stat !== 'player'
                    && $stat !== 'games'
                    && $stat !== 'number';
            }, ARRAY_FILTER_USE_KEY))
        );
    
        return $playerObject;
    }

    public function __invoke(array $player)
    {
        return static::makeFrom($player);
    }
}
