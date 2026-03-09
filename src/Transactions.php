<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use ArrayAccess;
use ArrayIterator;
use Countable;
use DateTime;
use Exception;
use IteratorAggregate;
use Traversable;

class Transactions implements Countable, IteratorAggregate, ArrayAccess
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

    public function total(bool $cost) : float
    {
        $total = 0;

        foreach ($this->list as $transaction) {
            $total += $transaction->value($cost);
        }

        return $total;
    }

    public function totalOn(DateTime $date, bool $cost) : float
    {
        $total = 0;

        foreach ($this->list as $transaction) {
            if ($transaction->date() > $date) {
                continue;
            }

            $total += $transaction->value($cost);
        }

        return $total;
    }

    public function acquisitionCost() : float
    {
        return $this->shares() * $this->shareUnitCost();
    }

    public function shareUnitCost() : float
    {
        $shares = 0;
        $value = 0;

        foreach ($this->list as $transaction) {
            $units = $transaction->shares();

            // only purchases count
            if ($units < 0) {
                continue;
            }

            $shares += $units;
            $value += $transaction->value(true);
        }

        return $value / $shares;
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

    public function report(bool $cost, string $title = '') : string
    {
        for ($i = 0; $i < count($this->list); ++$i) {
            $transaction = $this->list[$i];
            $data[] = $transaction->data($cost);
        }

        $data[] = [
            '-',
        ];

        $shares = $this->shares();
        $total = $this->total(true);

        if ($shares === 0) {
            $data[] = [
                'REALIZED GAIN',
                '',
                '',
                (int) round(-$total, 0, PHP_ROUND_HALF_UP),
            ];
        } else {
            $data[] = [
                'ACQU. COST',
                '',
                '',
                $this->shareUnitCost(),
            ];

            /*
            $data[] = [
                'ACQU. COST',
                $shares,
                '*',
                $unitCost,
                '=',
                (int) round($shares * $unitCost, 0, PHP_ROUND_HALF_UP),
            ];

            $data[] = [
                'TOTAL',
                $shares,
                '*',
                $total / $shares,
                '=',
                (int) round($total, 0, PHP_ROUND_HALF_UP),
            ];
            */
        }

        if (empty($title)) {
            $title = 'TRANSACTIONS';
        }

        return "{$title}\n" . new Table($data);
    }

    public function sort() : void
    {
        usort($this->list, static function ($transaction1, $transactions2) : int {
            return $transaction1->date() > $transactions2->date() ? +1 : -1;
        });
    }

    public function count() : int
    {
        return count($this->list);
    }

    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->list);
    }

    public function offsetExists(mixed $offset) : bool
    {
        return isset($this->list[$offset]);
    }

    public function offsetGet(mixed $offset) : mixed
    {
        return $this->list[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value) : void
    {
        throw new Exception('not implemented');
    }

    public function offsetUnset(mixed $offset) : void
    {
        throw new Exception('not implemented');
    }
}
