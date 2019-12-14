<?php
namespace AflUtils\Processors;

interface Processor
{
    public function __invoke($data);
}
