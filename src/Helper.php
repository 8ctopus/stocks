<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

class Helper
{
    private static string $reset = "\033[0m";
    private static string $green = "\033[0;32m";
    private static string $red = "\033[0;31m";

    public static function sprintf(string $format, float $value) : string
    {
        $str = sprintf($format, $value);

        if ($value > 0) {
            return self::$green . $str . self::$reset;
        } elseif ($value < 0) {
            return self::$red . $str . self::$reset;
        }
    }
}
