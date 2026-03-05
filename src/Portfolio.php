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

    public function realizedGain() : float
    {
        $realized = 0;

        foreach ($this->positions as $position) {
            $realized += $position->realizedGain();
        }

        return $realized;
    }

    public function dividends() : float
    {
        $dividends = 0;

        foreach ($this->positions as $position) {
            $dividends += $position->dividends();
        }

        return $dividends;
    }

    public function summary() : array
    {
        $currentValue = $this->currentValue();

        $data[] = [
            'CURRENT VALUE',
            (int) $currentValue,
        ];

        $acquisitionCost = $this->acquisitionCost();

        $data[] = [
            'ACQUISITION COST',
            (int) $acquisitionCost,
        ];

        $realized = $this->realizedGain();

        $data[] = [
            'REALIZED GAIN',
            (int) $realized,
            Helper::sprintf('%+.1f%%', 100 * $realized / $acquisitionCost),
        ];

        $latent = $currentValue - $acquisitionCost;

        $data[] = [
            'LATENT GAIN',
            (int) $latent,
            Helper::sprintf('%+.1f%%', 100 * $latent / $acquisitionCost),
        ];

        $dividends = $this->dividends();

        $data[] = [
            'DIVIDEND INCOME',
            (int) $dividends,
            Helper::sprintf('%+.1f%%', 100 * $dividends / $acquisitionCost),
        ];

        $data[] = [
            'TOTAL RETURN',
            (int) ($latent + $dividends),
            Helper::sprintf('%+.1f%%', 100 * ($realized + $latent + $dividends) / $acquisitionCost),
        ];

        return $data;
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
