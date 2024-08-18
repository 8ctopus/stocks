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
        [$width, $totalWidth] = $this->measure();

        foreach ($this->data as $row) {
            $index = 0;

            foreach ($row as $index => $cell) {
                if ($index === 0 && $cell === '-') {
                    $output .= str_pad('', $totalWidth + 1, '-', STR_PAD_LEFT);
                    break;
                }

                $padding = ($index === 0 && is_string($cell)) ? STR_PAD_RIGHT : STR_PAD_LEFT;

                $output .= str_pad($this->toStr($cell), $width[$index] + ($index ? 1 : 0), ' ', $padding);
                ++$index;
            }

            $output .= "\n";
        }

        return $output;
    }

    private function measure() : array
    {
        $widths = [];

        foreach ($this->data as $row) {
            if (!isset($columns)) {
                $columns = count($row);
            }

            $index = 0;

            foreach ($row as $cell) {
                $widths[$index] = max($widths[$index] ?? 0, strlen($this->toStr($cell)));
                ++$index;
            }
        }

        return [
            $widths,
            array_sum($widths) + $columns -1,
        ];
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
