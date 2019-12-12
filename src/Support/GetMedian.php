<?php
namespace AflUtils\Support;

use Ds\Deque;

class GetMedian
{
    /**
     * Returns the median key or keys from the given collection.
     *
     * @param Deque $collection
     *
     * @return int[]|int
     */
    public static function from(Deque $collection)
    {
        $total = $collection->count();
        if (is_int($mid = $total / 2)) {
            return [$mid - 1, $mid];
        } else {
            return $collection[(int) floor($mid)];
        }
    }
}
