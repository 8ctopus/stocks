<?php

namespace Oct8pus\Stocks;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class DividendHistory implements ArrayAccess, IteratorAggregate
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

    public function offsetExists(mixed $offset) : bool
    {
        return $offset < count($this->list) - 1;
    }

    public function offsetGet(mixed $offset) : Dividend
    {
        return $this->list[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value) : void
    {
        $this->list[$offset] = $value;
    }

    public function offsetUnset(mixed $offset) : void
    {
        unset($this->list[$offset]);
    }
}
