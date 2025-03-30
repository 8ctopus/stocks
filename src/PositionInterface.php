<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

interface PositionInterface
{
    public function ticker() : string;

    public function price() : float;

    public function setPrice(float $price) : void;

    public function currentValue() : float;

    public function summary() : array;
}
