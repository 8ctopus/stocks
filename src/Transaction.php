<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use DateTime;

class Transaction
{
    private readonly DateTime $date;
    private readonly int $shares;
    private readonly float $price;

    public function __construct(DateTime $date, int $shares, float $price)
    {
        $this->date = $date;
        $this->shares = $shares;
        $this->price = $price;
    }

    public function date() : DateTime
    {
        return $this->date;
    }

    public function shares() : int
    {
        return $this->shares;
    }

    public function value() : float
    {
        return $this->price * $this->shares;
    }

    public function data() : array
    {
        return [
            'date' => $this->date,
            'shares' => $this->shares,
            'price' => $this->price,
            'total' => (int) $this->value(),
        ];
    }

    public function __toString() : string
    {
        $date = $this->date->format('d.m.Y');
        $shares = sprintf('%5d', $this->shares);
        $price = sprintf('%6.2f', $this->price);
        $total = str_pad(number_format($this->value(), 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

        return "{$date} {$shares} * {$price} = {$total}\n";
    }
}
