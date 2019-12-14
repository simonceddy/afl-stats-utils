<?php
namespace AflUtils\Pipeline;

use AflUtils\Processors\Processor;

class Pipe implements Processor
{
    protected $stack = [];

    protected $failed = [];

    /**
     * Create a new Pipe
     *
     * @param Processor[]|callable[] $processors Array of processors objects and functions
     */
    public function __construct(array $processors = [])
    {
        empty($processors) ?: $this->push(...$processors);
    }

    public function push(callable ...$processors)
    {
        array_push($this->stack, ...$processors);
        return $this;
    }

    public function addAtPos(callable $processor, int $pos)
    {
        if ($pos <= 0) {
            array_unshift($this->stack, $processor);
            return $this;
        }

        if ($pos < count($this->stack)) {
            $endStack = array_splice($this->stack, $pos);
            return $this->push($processor, ...$endStack);

        }

        return $this->push($processor);

    }

    public function add(callable $processor, int $pos = -1) {
        if ($pos >= 0) {
            return $this->addAtPos($processor, $pos);
        }
        return $this->push($processor);
    }

    public function execute($payload)
    {
        foreach ($this->stack as $pos => $processor) {
            try {
                $payload = call_user_func($processor, $payload);
            } catch (\Exception $e) {
                $this->failed[] = [$pos, $e];
            }
        }

        if (!empty($this->failed)) {
            dd($this->failed);
        }

        return $payload;
    }

    public function __invoke($data)
    {
        return $this->execute($data);
    }
}
