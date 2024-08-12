<?php

declare(strict_types=1);

namespace Oct8pus\Stocks;

class Table
{
    private readonly array $data;
    private readonly ?string $title;

    public function __construct(array $data, ?string $title = null)
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function render() : string
    {
        $output = isset($this->title) ? "{$this->title}\n" : '';
        $widths = $this->calculateWidth();

        foreach ($this->data as $row) {
            $index = 0;

            foreach ($row as $cell) {
                $output .= str_pad($this->toStr($cell), $widths[$index] + ($index ? 1 : 0), ' ', STR_PAD_LEFT);
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
            $index = 0;

            foreach ($row as $cell) {
                $widths[$index] = max($widths[$index] ?? 0, strlen($this->toStr($cell)));
                ++$index;
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

    public function __toString() : string
    {
        return $this->render();
    }
}
