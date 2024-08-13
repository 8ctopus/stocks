<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use DateTime;
use Exception;

class Position
{
    private readonly string $ticker;
    private readonly ?float $price;
    private readonly Transactions $transactions;
    private readonly ?DividendHistory $history;

    public function __construct(string $ticker, float $price, Transactions $transactions)
    {
        $this->ticker = $ticker;
        $this->price = $price;
        $this->transactions = $transactions;
    }

    public function setDividendHistory(DividendHistory $history) : self
    {
        $this->history = $history;
        return $this;
    }

    public function ticker() : string
    {
        return $this->ticker;
    }

    public function shares() : int
    {
        return $this->transactions->shares();
    }

    public function currentValue() : float
    {
        return $this->transactions->shares() * $this->price;
    }

    public function acquistionCost() : float
    {
        return $this->transactions->total();
    }

    public function dividendsPaid() : string
    {
        $total = 0;

        foreach ($this->history as $item) {
            $date = $item->date();
            $shares = $this->transactions->sharesOn($date);

            $dividendPerShare = $item->dividend();
            $dividend = $shares * $dividendPerShare;
            $total += $dividend;
        }

        return $total;
    }

    public function sharesOn(DateTime $date) : int
    {
        return $this->transactions->sharesOn($date);
    }

    public function report(string $type) : string
    {
        switch ($type) {
            case 'transactions':
                return $this->transactions->report();

            case 'profit':
                return $this->reportSharePriceProfit();

            case 'dividends':
                return $this->reportDividends();

            default:
                throw new Exception();
        }
    }

    private function reportSharePriceProfit() : string
    {
        if ($this->price === null) {
            throw new Exception('stock price not set');
        }

        $data[] = [
            '          ',
            $this->transactions->shares(),
            '*',
            $this->price,
            '=',
            (int) $this->currentValue(),
            '',
        ];

        $difference = $this->currentValue() - $this->transactions->total();

        $data[] = [
            'UNREALIZED',
            '',
            '',
            '',
            '',
            (int) $difference,
            sprintf('(%+.1f%%)', 100 * $difference / $this->transactions->total())
        ];

        return new Table($data, 'CURRENT VALUE') . "\n";
    }

    public function reportDividends() : string
    {
        $data = [];
        $total = 0;

        foreach ($this->history as $item) {
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

        return (string) new Table($data, 'DIVIDENDS');
    }
}
