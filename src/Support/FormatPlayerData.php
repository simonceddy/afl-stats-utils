<?php
namespace AflUtils\Support;

class FormatPlayerData
{
    public static function process(iterable $player)
    {
        dd($player);
        $games = isset($player['games']) ? $player['games'] : 0;
        return [
            $player['player'],
            $games,
            $player['stats'],
        ];
    }

    public function __invoke(iterable $data)
    {
        return static::process($data);
    }
}
