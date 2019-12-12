<?php
namespace AflUtils;

use Ds\Map;

class AflPlayer implements \JsonSerializable
{
    protected array $names;

    protected int $games;

    protected int $number;

    protected Map $stats;

    protected float $timePlayed;

    public function __construct(
        array $names,
        int $number,
        int $games,
        Map $stats,
        float $timePlayed
    ) {
        $this->names = $names;
        $this->number = $number;
        $this->games = $games;
        $this->stats = $stats;
        $this->timePlayed = $timePlayed;
    }

    

    /**
     * Get the value of names
     */ 
    public function getNames()
    {
        return $this->names;
    }

    /**
     * Get the value of games
     */ 
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Get the value of number
     */ 
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Get the value of stats
     */ 
    public function getStats()
    {
        return $this->stats;
    }

    public function jsonSerialize()
    {
        return [
            'player' => $this->names,
            'number' => $this->number,
            'games' => $this->games,
            'stats' => $this->stats,
            'timePlayed' => $this->timePlayed,
        ];
    }

    /**
     * Get the value of timePlayed
     */ 
    public function getTimePlayed()
    {
        return $this->timePlayed;
    }
}
