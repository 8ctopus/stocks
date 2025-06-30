<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use DateTime;
use DivisionByZeroError;
use Exception;

class Position implements PositionInterface
{
    private readonly string $ticker;
    private float $price;
    private float $priceYearOpen;
    private readonly Transactions $transactions;
    private readonly ?DividendHistory $history;

    /**
     * Constructor
     *
     * @param string       $ticker
     * @param float        $price        - current stock price
     * @param Transactions $transactions
     */
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

    public function price() : float
    {
        return $this->price;
    }

    public function dividendHistory() : ?DividendHistory
    {
        return $this->history;
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

    public function acquisitionCostOn(DateTime $date) : float
    {
        return $this->transactions->totalOn($date);
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

    public function summary(?float $portfolioValue = null) : array
    {
        if ($this->price === null) {
            throw new Exception('stock price not set');
        }

        $data[] = [
            $this->ticker,
            $this->shares(),
        ];

        $data[] = [
            'SHARE YTD',
            $this->price(),
            Helper::sprintf('%+.1f%%', 100 * ($this->price - $this->priceYearOpen()) / $this->priceYearOpen()),
        ];

        $acquisitionCost = $this->acquisitionCost();

        $data[] = [
            'ACQUISITION COST',
            (int) $acquisitionCost,
        ];

        $currentValue = $this->currentValue();

        $data[] = [
            'CURRENT VALUE',
            (int) $currentValue,
        ];

        $profit = $currentValue - $acquisitionCost;

        try {
            $percentage = Helper::sprintf('%+.1f%%', 100 * $profit / $acquisitionCost);
        } catch (DivisionByZeroError) {
            $percentage = Helper::sprintf('+∞', 0);
        }

        $data[] = [
            'CAPITAL GAIN',
            (int) $profit,
            $percentage,
        ];

        $dividends = $this->dividends();

        try {
            $percentage = Helper::sprintf('%+.1f%%', 100 * $dividends / $acquisitionCost);
        } catch (DivisionByZeroError) {
            $percentage = Helper::sprintf('+∞', 0);
        }

        $data[] = [
            'DIVIDEND INCOME',
            (int) $dividends,
            $percentage,
        ];

        try {
            $percentage = Helper::sprintf('%+.1f%%', 100 * ($profit + $dividends) / $acquisitionCost);
        } catch (DivisionByZeroError) {
            $percentage = Helper::sprintf('+∞', 0);
        }

        $data[] = [
            'TOTAL RETURN',
            (int) ($profit + $dividends),
            $percentage,
        ];

        if ($portfolioValue) {
            $data[] = [
                'PART OF PORTFOLIO',
                sprintf('%.1f%%', 100 * $currentValue / $portfolioValue),
            ];
        }

        return $data;
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
                $date,
                $shares,
                '*',
                $dividendPerShare,
                '=',
                (int) round($dividend, 0, PHP_ROUND_HALF_UP),
            ];
        }

        $data[] = ['-'];

        $data[] = [
            '',
            '',
            '',
            '',
            '',
            (int) $total,
            Helper::sprintf('%+.1f%%', 100 * $total / $this->acquisitionCost()),
        ];

        return (string) new Table($data, "{$this->ticker} DIVIDEND INCOME");
    }

    public function reportTransactions() : string
    {
        return $this->transactions->report($this->ticker);
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

    public function transactions() : Transactions
    {
        return $this->transactions;
    }

    public function cleanup() : void
    {
        $this->transactions->sort();
        $this->history->sort();
    }

    // FIX ME
    public function priceYearOpen() : float
    {
        if (!isset($this->priceYearOpen)) {
            $stdin = fopen('php://stdin', 'r');

            if ($stdin === false) {
                throw new Exception('fopen');
            }

            echo "{$this->ticker} year opening> ";
            $this->priceYearOpen = (float) trim(fgets($stdin));

            fclose($stdin);
        }

        return $this->priceYearOpen;
    }
}
