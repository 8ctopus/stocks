<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use DateTime;

class Cash implements PositionInterface
{
    private readonly string $ticker;
    private float $price;

    public function __construct()
    {
        $this->ticker = 'CASH';
    }

    public function ticker() : string
    {
        return $this->ticker;
    }

    public function price() : float
    {
        return $this->price;
    }

    public function setPrice(float $price) : void
    {
        $this->price = $price;
    }

    public function currentValue() : float
    {
        return $this->price;
    }

    public function acquisitionCost() : float
    {
        return $this->price;
    }

    public function acquisitionCostOn(DateTime $date) : float
    {
        return $this->price;
    }

    public function dividends() : float
    {
        return 0;
    }

    public function reportDividends(?int $year = null) : string
    {
        return '';
    }

    public function summary(?float $portfolioValue = null) : array
    {
        $data[] = [
            $this->ticker,
            (int) $this->price,
        ];

        if ($portfolioValue) {
            $data[] = [
                'PART OF PORTFOLIO',
                sprintf('%.1f%%', 100 * $this->currentValue() / $portfolioValue),
            ];
        }

        return $data;
    }
}
