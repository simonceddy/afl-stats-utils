<?php
namespace AflUtils\Support;

use AflUtils\Utils\FloatToRational;
use MathPHP\Number\ArbitraryInteger;
use MathPHP\Number\Rational;
use Moontoast\Math\BigNumber;

class GetGeometricMean
{
    /**
     * Returns the geometric mean from a collection of numbers.
     *
     * @param Deque $data
     *
     * @return float
     */
    public static function from(iterable $data)
    {
        $number = null;

        foreach ($data as $num) {
            if ($num === 0) {
                continue;
            }

            if (is_float($num)) {
                // dd($number);
            }

            [$n, $d] = FloatToRational::caluclate($num);

            $num = new Rational(0, (int) $n, (int) $d);

            if ($number === null) {
                $number = $num;
            } else {
                $number = $number->multiply($num);
            }
            // dd($number);

        }

        dd($number);
        dd($number->pow(1 / count($data)));
        return $number->pow(1 / count($data));
    }
}
