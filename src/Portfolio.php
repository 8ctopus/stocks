<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

use ArrayIterator;
use Exception;
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

    public function add(Position $position) : self
    {
        $this->positions[] = $position;
        return $this;
    }

    public function delete(Position $position) : self
    {
        if (($position = array_search($position, $this->positions, true)) !== false) {
            unset($this->positions[$position]);
        }

        return $this;
    }

    public function currentValue() : float
    {
        throw new Exception('not implemented');
    }

    public function report(string $type) : string
    {
        switch ($type) {
            default:
                throw new Exception('not implemented');
        }
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
