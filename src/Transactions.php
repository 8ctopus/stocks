<?php

namespace Oct8pus\Stocks;

use DateTime;

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

    public function sharesOn(DateTime $date) : int
    {
        $units = 0;

        foreach ($this->list as $transaction) {
            if ($transaction->date() > $date) {
                continue;
            }

            $units += $transaction->shares();
        }

        return $units;
    }

    public function total() : float
    {
        $total = 0;

        foreach ($this->list as $transaction) {
            $total += $transaction->value();
        }

        return $total;
    }

    public function price() : float
    {
        $shares = $this->shares();

        return $shares ? $this->total() / $shares : $this->total();
    }

    public function summary() : string
    {
        $shares = $this->shares();

        if ($shares === 0) {
            $total = str_pad(number_format(- $this->total(), 0, '.', '\''), 8, ' ', STR_PAD_LEFT);
            return "PROFIT                      {$total}\n";
        } else {
            $sharesFormatted = sprintf('%5d', $shares);
            $price = sprintf('%6.2f', $this->price());
            $total = str_pad(number_format($this->total(), 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

            return "COST       {$sharesFormatted} * {$price} = {$total}\n";
        }
    }

    public function detailed() : string
    {
        $output = '';

        for ($i = count($this->list); $i > 0; --$i) {
            $transaction = $this->list[$i - 1];
            $output .= $transaction;
        }

        $output .= "------------------------------------\n";

        return $output . $this->summary();
    }
}
