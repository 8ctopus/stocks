<?php

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

    public function __toString() : string
    {
        $output = '';

        for ($i = count($this->list); $i > 0; --$i) {
            $output .= $this->list[$i -1];
        }

        return $output . "\n";
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->list);
    }
}
