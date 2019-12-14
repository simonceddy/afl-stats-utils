<?php
namespace AflUtils\Processors\Players;

use AflUtils\AflPlayer;
use AflUtils\Processors\PlayerProcessor;
use AflUtils\Support\Traits\HasMapStorage;
use Ds\Deque;
use Ds\Map;

class TotalsCounter implements PlayerProcessor
{
    use HasMapStorage;

    public function __construct(Map $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(AflPlayer $player): AflPlayer
    {
        $stats = $player->getStats();
        foreach ($stats as $stat => $value) {
            isset($this->storage[$stat]) ?: $this->storage[$stat] = new Deque();
            $this->storage[$stat]->push($value);
        }

        return $player;
    }
}
