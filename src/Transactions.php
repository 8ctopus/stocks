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

    public function total() : float
    {
        $total = 0;

        foreach ($this->list as $transaction) {
            $total += $transaction->value();
        }

        return $total;
    }

    public function totalOn(DateTime $date) : float
    {
        $total = 0;

        foreach ($this->list as $transaction) {
            if ($transaction->date() > $date) {
                continue;
            }

            $total += $transaction->value();
        }

        return $total;
    }

    public function averageSharePrice() : float
    {
        $shares = $this->shares();

        return $shares ? $this->total() / $shares : $this->total();
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

    public function report(string $title = '') : string
    {
        for ($i = 0; $i < count($this->list); ++$i) {
            $transaction = $this->list[$i];
            $data[] = $transaction->data();
        }

        $data[] = [
            '-',
        ];

        $shares = $this->shares();

        if ($shares === 0) {
            $data[] = [
                'PROFIT',
                '',
                '',
                $this->total(),
            ];
        } else {
            $data[] = [
                'COST',
                $shares,
                '*',
                $this->averageSharePrice(),
                '=',
                (int) $this->total(),
            ];
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
