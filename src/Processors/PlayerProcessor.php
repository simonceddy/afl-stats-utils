<?php
namespace AflUtils\Processors;

use AflUtils\AflPlayer;

interface PlayerProcessor
{
    public function __invoke(AflPlayer $player): AflPlayer;
}
