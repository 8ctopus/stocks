<?php

namespace Oct8pus\Stocks;

use DateTime;

class Position
{
    private readonly string $ticker;
    private readonly ?float $price;
    private readonly Transactions $transactions;

    public function __construct(string $ticker, ?float $price = null, ?Transactions $transactions = null)
    {
        $this->ticker = $ticker;
        $this->price = $price;
        $this->transactions = $transactions ?? new Transactions();
    }

    public function value() : float
    {
        return $this->transactions->shares() * $this->price;
    }

    public function sharesOn(DateTime $date) : int
    {
        return $this->transactions->sharesOn($date);
    }

    public function detailed() : string
    {
        $output = <<<TXT
        {$this->ticker}


        TXT;

        $output .= $this->transactions->detailed();

        if ($this->price !== null) {
            $price = sprintf('%6.2f', $this->price);
            $total = str_pad(number_format($this->value(), 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

            $output .= "CURRENT            {$price} = {$total}\n";

            $difference = $this->value() - $this->transactions->total();
            $differenceFormatted = str_pad(number_format($difference, 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

            $percentage = sprintf('%+.1f', 100 * $difference / $this->transactions->total());
            $output .= "UNREALIZED                  {$differenceFormatted} ($percentage%)\n\n";
        }

        return $output;
    }
}
