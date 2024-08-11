<?php

namespace Oct8pus\Stocks;

class Transactions
{
    private array $list;

    public function __construct()
    {
        $this->list = [];
    }

    public function add(Transaction $transaction) : self
    {
        $this->list[] = $transaction;

        return $this;
    }

    public function shares() : int
    {
        $units = 0;

        foreach ($this->list as $transaction) {
            $units += $transaction->shares();
        }

        return $units;
    }

    public function total() : float
    {
        $total = 0;

        foreach ($this->list as $transaction) {
            $total += $transaction->total();
        }

        return $total;
    }

    public function price() : float
    {
        return $this->total() / $this->shares();
    }

    public function summary() : string
    {
        $shares = sprintf('%5d', $this->shares());
        $price = sprintf('%6.2f', $this->price());
        $total = str_pad(number_format($this->total(), 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

        return "           {$shares} * {$price} = {$total}\n";
    }

    public function detailed() : string
    {
        $output = '';

        foreach ($this->list as $transaction) {
            $output .= $transaction;
        }

        $output .= "------------------------------------\n";

        return $output . $this->summary();
    }
}
