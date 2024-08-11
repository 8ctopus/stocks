<?php

namespace Oct8pus\Stocks;

use DateTime;

class DividendHistory
{
    private readonly string $ticker;
    private array $history;

    public function __construct(string $ticker)
    {
        $this->ticker = $ticker;
        $this->history = [];
    }

    public function add(DateTime $date, float $dividend) : self
    {
        $this->history[] = [
            'date' => $date,
            'dividend' => $dividend,
        ];

        return $this;
    }

    public function __toString() : string
    {
        $output = '';

        for ($i = count($this->history); $i > 0; --$i) {
            $point = $this->history[$i -1];
            $dividend = sprintf('%.2f', $point['dividend']);
            $output .= $point['date']->format('d.m.Y') . " {$dividend}\n";
        }

        return $output;
    }
}
