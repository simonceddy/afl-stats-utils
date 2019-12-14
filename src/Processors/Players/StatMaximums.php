<?php
namespace AflUtils\Processors\Players;

use AflUtils\AflPlayer;
use AflUtils\Processors\PlayerProcessor;
use AflUtils\Support\Constants;
use AflUtils\Support\Traits\HasMapStorage;
use Ds\Map;

class StatMaximums implements PlayerProcessor
{
    use HasMapStorage;

    public function __construct(Map $storage)
    {
        $this->storage = $storage;
    }

    private function initStatInStorage(string $stat)
    {
        $this->storage[$stat] = new Map([
            'total' => new Map([
                'player' => null,
                'value' => 0,
                'games' => 0
            ]),
            'perGame' => new Map([
                'player' => null,
                'value' => 0,
                'games' => 0
            ]),
        ]);
    }

    public function __invoke(AflPlayer $player): AflPlayer
    {
        $stats = $player->getStats();
        $games = $player->getGames();
        $name = $player->getNames();

        foreach ($stats as $stat => $value) {
            if (!is_numeric($value)) {
                continue;
            }
            $perGame = round($value / $games, Constants::STAT_PRECISION);
            
            isset($this->storage[$stat]) ?: $this->initStatInStorage($stat);

            if ($perGame > $this->storage[$stat]['perGame']['value']) {
                $this->storage[$stat]['perGame'] = new Map([
                    'player' => $name,
                    'value' => $perGame,
                    'games' => $games
                ]);
            }
            if ($value > $this->storage[$stat]['total']['value']) {
                $this->storage[$stat]['total'] = new Map([
                    'player' => $name,
                    'value' => $value,
                    'games' => $games
                ]);
            }
        }

        return $player;
    }
}
