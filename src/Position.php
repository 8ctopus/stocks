<?php

namespace Oct8pus\Stocks;

class Position
{
    private readonly string $ticker;
    private readonly Transactions $transactions;

    public function __construct(string $ticker, ?Transactions $transactions = null)
    {
        $this->ticker = $ticker;
        $this->transactions = $transactions ?? new Transactions();
    }

    public function transactions() : Transactions
    {
        return $this->transactions;
    }

    public function detailed() : string
    {
        $output = <<<TXT
        {$this->ticker}


        TXT;

        return $output . $this->transactions->detailed();
    }
}
