<?php

declare(strict_types=1);

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

    public function total() : float
    {
        $total = 0;

        foreach ($this->list as $transaction) {
            $total += $transaction->value();
        }

        return $total;
    }

    public function averageSharePrice() : float
    {
        $shares = $this->shares();

        return $shares ? $this->total() / $shares : $this->total();
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

    public function report() : string
    {
        $data = [];

        for ($i = count($this->list); $i > 0; --$i) {
            $transaction = $this->list[$i - 1];
            $data[] = $transaction->data();
        }

        $data[] = [
            '-',
        ];

        $shares = $this->shares();

        if ($shares === 0) {
            $data[] = [
                'PROFIT',
                '',
                '',
                $this->total(),
            ];
        } else {
            $data[] = [
                "COST",
                $shares,
                '*',
                $this->averageSharePrice(),
                '=',
                (int) $this->total(),
            ];
        }

        return "TRANSACTIONS\n" . new Table($data);
    }
}
