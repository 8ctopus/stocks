<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use DateTime;
use Exception;

class Position
{
    private readonly string $ticker;
    private ?float $price;
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

    public function setPrice(float $price) : void
    {
        $this->price = $price;
    }

    public function currentValue() : float
    {
        return $this->transactions->shares() * $this->price;
    }

    public function acquisitionCost() : float
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
                return $this->transactions->report("{$this->ticker} TRANSACTIONS");

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

        $currentValue = $this->currentValue();

        $data[] = [
            'CURRENT VALUE',
            $this->transactions->shares(),
            '*',
            $this->price,
            '=',
            (int) $currentValue,
            '',
        ];

        $acquisitionCost = $this->acquisitionCost();

        $data[] = [
            'ACQUISITION COST',
            '',
            '',
            '',
            '',
            (int) $acquisitionCost,
        ];

        $profit = $currentValue - $acquisitionCost;

        $data[] = [
            'UNREALIZED PROFIT',
            '',
            '',
            '',
            '',
            (int) $profit,
            sprintf('(%+.1f%%)', 100 * $profit / $acquisitionCost)
        ];

        return new Table($data) . "\n";
    }

    public function reportDividends(?int $year = null) : string
    {
        $data = [];
        $total = 0;

        foreach ($this->history as $item) {
            $date = $item->date();

            if ($year && $year !== (int) $date->format('Y')) {
                continue;
            }

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

        $data[] = ['-'];

        $data[] = [
            'date' => '',
            'shares' => '',
            '*' => '',
            'dividend per share' => '',
            '=' => '',
            'dividend' => (int) $total,
            'percentage' => sprintf('(%+.1f%%)', 100 * $total / $this->acquisitionCost()),
        ];

        return (string) new Table($data, "{$this->ticker} DIVIDENDS");
    }

    public function dividends(?int $year = null) : float
    {
        $total = 0;

        foreach ($this->history as $item) {
            $date = $item->date();

            if ($year && $year !== (int) $date->format('Y')) {
                continue;
            }

            $shares = $this->transactions->sharesOn($date);

            $dividendPerShare = $item->dividend();
            $dividend = $shares * $dividendPerShare;
            $total += $dividend;
        }

        return $total;
    }

    public function sharePriceProfit() : float
    {
        return $this->currentValue() - $this->acquisitionCost();
    }
}
