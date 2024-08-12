<?php

declare(strict_types=1);

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

    public function currentValue() : float
    {
        return $this->transactions->shares() * $this->price;
    }

    public function sharesOn(DateTime $date) : int
    {
        return $this->transactions->sharesOn($date);
    }

    public function detailed() : string
    {
        $output = "{$this->ticker}\n\n";
        $output .= $this->transactions->detailed();

        if ($this->price !== null) {
            $data[] = [
                'CURRENT VALUE',
                $this->price,
                $this->currentValue(),
                '',
            ];

            $difference = $this->currentValue() - $this->transactions->total();

            $data[] = [
                'UNREALIZED',
                '',
                $difference,
                sprintf('(%+.1f%%)', 100 * $difference / $this->transactions->total())
            ];

            /* REM
            $price = sprintf('%6.2f', $this->price);
            $total = str_pad(number_format($this->currentValue(), 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

            $output .= "CURRENT VALUE      {$price} = {$total}\n";

            $difference = $this->currentValue() - $this->transactions->total();
            $differenceFormatted = str_pad(number_format($difference, 0, '.', '\''), 8, ' ', STR_PAD_LEFT);

            $percentage = sprintf('%+.1f', 100 * $difference / $this->transactions->total());
            $output .= "UNREALIZED                  {$differenceFormatted} ($percentage%)\n\n";
            */

            $output .= (new TableFormat($data))->render();
        }

        return $output . "\n";
    }

    public function dividends(DividendHistory $history) : string
    {
        $data = [];
        $total = 0;

        foreach ($history as $item) {
            $date = $item->date();
            $shares = $this->transactions->sharesOn($date);

            $dividendPerShare = $item->dividend();
            $dividend = $shares * $dividendPerShare;
            $total += $dividend;

            $data[] = [
                'date' => $date,
                'shares' => $shares,
                '*' => '*',
                'dividend per share' => $dividendPerShare,
                '=' => '=',
                'dividend' => (int) round($dividend, 0, PHP_ROUND_HALF_UP),
                'percentage' => '',
            ];
        }

        $data[] = [
            'date' => '',
            'shares' => '',
            '*' => '',
            'dividend per share' => '',
            '=' => '',
            'dividend' => (int) $total,
            'percentage' => sprintf('(%+.1f%%)', 100 * $total / $this->currentValue()),
        ];

        return "DIVIDENDS\n" . (new TableFormat($data))->render();
    }
}
