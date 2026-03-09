<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use DateTime;
use Exception;

class Transaction
{
    private readonly DateTime $date;
    private readonly int $shares;
    private readonly float $price;

    // FIX ME - set to readonly once all costs were imported
    private float $cost;

    public function __construct(DateTime $date, int $shares, float $price, float $cost = 0)
    {
        $this->date = $date;
        $this->shares = $shares;
        $this->price = $price;
        $this->cost = $cost;
    }

    public function __toString() : string
    {
        $date = $this->date->format('d.m.Y');
        $shares = Helper::sprintf('%5d', $this->shares);
        $price = Helper::sprintf('%6.2f', $this->price);
        $total = str_pad(number_format($this->value(false), 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

        return "{$date} {$shares} * {$price} = {$total}\n";
    }

    public function date() : DateTime
    {
        return $this->date;
    }

    public function shares() : int
    {
        return $this->shares;
    }

    public function price() : float
    {
        return $this->price;
    }

    public function cost() : float
    {
        return $this->cost;
    }

    public function value(bool $cost) : float
    {
        $value = $this->price * $this->shares;

        if (!$cost) {
            return $value;
        }

        return $value > 0 ? $value + $this->cost : $value - $this->cost;
    }

    public function data() : array
    {
        return [
            $this->date,
            $this->shares,
            '*',
            $this->price,
            '=',
            (int) round($this->value(false), 0, PHP_ROUND_HALF_UP),
        ];
    }

    public function __serialize() : array
    {
        return [
            'date' => $this->date,
            'shares' => $this->shares,
            'price' => $this->price,
            'cost' => $this->cost,
        ];
    }

    public function __unserialize(array $data) : void
    {
        $this->date = $data['date'];
        $this->shares = $data['shares'];
        $this->price = $data['price'];
        $this->cost = $data['cost'];
    }
}
