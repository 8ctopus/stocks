<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Portfolio implements IteratorAggregate
{
    private static string $file = __DIR__ . '/../portfolio.dat';
    private array $positions;

    public function __construct()
    {
        $this->positions = [];
    }

    public function add(PositionInterface $position) : self
    {
        $this->positions[] = $position;
        return $this;
    }

    public function delete(PositionInterface $position) : self
    {
        if (($position = array_search($position, $this->positions, true)) !== false) {
            unset($this->positions[$position]);
        }

        return $this;
    }

    public function currentValue() : float
    {
        $currentValue = 0;

        foreach ($this->positions as $position) {
            $currentValue += $position->currentValue();
        }

        return $currentValue;
    }

    public function acquisitionCost() : float
    {
        $acquisitionCost = 0;

        foreach ($this->positions as $position) {
            $acquisitionCost += $position->acquisitionCost();
        }

        return $acquisitionCost;
    }

    public function dividends() : float
    {
        $dividends = 0;

        foreach ($this->positions as $position) {
            $dividends += $position->dividends();
        }

        return $dividends;
    }

    public function summary() : string
    {
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

        $data[] = [
            'CAPITAL GAIN',
            (int) $profit,
            Helper::sprintf('%+.1f%%', 100 * $profit / $acquisitionCost),
        ];

        $dividends = $this->dividends();

        $data[] = [
            'DIVIDEND INCOME',
            (int) $dividends,
            Helper::sprintf('%+.1f%%', 100 * $dividends / $acquisitionCost),
        ];

        $data[] = [
            'TOTAL RETURN',
            (int) ($profit + $dividends),
            Helper::sprintf('%+.1f%%', 100 * ($profit + $dividends) / $acquisitionCost),
        ];

        return (string) new Table($data);
    }

    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->positions);
    }

    public static function load() : self
    {
        return unserialize(file_get_contents(self::$file));
    }

    public function save() : void
    {
        file_put_contents(self::$file, serialize($this));
    }
}
