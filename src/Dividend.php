<?php

namespace Oct8pus\Stocks;

use DateTime;

class Dividend
{
    private readonly DateTime $date;
    private readonly float $dividend;

    public function __construct(DateTime $date, float $dividend)
    {
        $this->date = $date;
        $this->dividend = $dividend;
    }

    public function __toString() : string
    {
        $dividend = sprintf('%.2f', $this->dividend);
        return $this->date->format('d.m.Y') . " {$dividend}\n";
    }
}
