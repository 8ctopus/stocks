<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Portfolio;
use Swew\Cli\SwewCommander;

class Commands extends SwewCommander
{
    protected array $commands = [
        PositionSummary::class,
        PositionList::class,
        Dividends::class,
        Transactions::class,
        UpdatePrice::class,
        ImportPosition::class,
    ];

    protected readonly Portfolio $portfolio;

    protected function init(): void
    {
        $this->portfolio = Portfolio::load();
    }

    public function portfolio() : Portfolio
    {
        return $this->portfolio;
    }
}
