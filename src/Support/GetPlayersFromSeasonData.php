<?php
namespace AflUtils\Support;

use AflUtils\Utils\GeneratorFactory;
use Ds\Deque;

class GetPlayersFromSeasonData
{
    public function process(iterable $data)
    {
        $players = new Deque();

        $generator = GeneratorFactory::create($data, function ($teamData) {
            if (!isset($teamData['players'])) {
                return null;
            }
            return GeneratorFactory::create(
                $teamData['players'],
                new PlayerBuilder()
            );
        });

        foreach ($generator as $playersGenerator) {
            foreach ($playersGenerator as $player) {
                $players->push($player);
            }
        }

        // dd($players[0]);

        /* foreach ($data as $teamData) {
            if (isset($teamData['players'])) {
                $players->push(...$teamData['players']);
            }
        } */

        return $players;
    }
}
