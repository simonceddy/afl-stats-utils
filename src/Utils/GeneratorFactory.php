<?php
namespace AflUtils\Utils;

final class GeneratorFactory
{
    public static function create(iterable $nodes, callable $callback)
    {
        foreach ($nodes as $key => $node) {
            yield call_user_func($callback, $node, $key);
        }
    }
}
