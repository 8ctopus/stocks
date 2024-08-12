<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class DividendHistory implements IteratorAggregate
{
    private array $list;

    public function __construct()
    {
        $this->list = [];
    }

    public function add(Dividend $dividend) : self
    {
        $this->list[] = $dividend;
        return $this;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->list);
    }

    public function __toString() : string
    {
        $data = [];

        for ($i = count($this->list); $i > 0; --$i) {
            $data[] = $this->list[$i -1]->data();
        }

        return (new Table($data))->render();
    }
}
