<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

class Table
{
    private readonly array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function render() : string
    {
        $output = '';
        $widths = $this->calculateWidth();

        foreach ($this->data as $row) {
            $index = 0;

            foreach ($row as $title => $cell) {
                $output .= str_pad($this->toStr($cell), $widths[$title] + ($index ? 1 : 0), ' ', STR_PAD_LEFT);
                ++$index;
            }

            $output .= "\n";
        }

        return $output;
    }

    private function calculateWidth() : array
    {
        $widths = [];

        foreach ($this->data as $row) {
            foreach ($row as $title => $cell) {
                $widths[$title] = max($widths[$title] ?? 0, strlen($this->toStr($cell)));
            }
        }

        return $widths;
    }

    private function toStr(mixed $cell) : string
    {
        switch (gettype($cell)) {
            case 'object':
                $value = $cell->format('d.m.Y');
                break;

            case 'integer':
                $value = number_format($cell, 0, '.', '\'');
                break;

            case 'double':
                $value = number_format($cell, 2, '.', '\'');
                break;

            default:
                $value = $cell;
                break;
        }

        return $value;
    }
}
