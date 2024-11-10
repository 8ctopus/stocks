<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class PositionSummary extends Command
{
    public const NAME = 'summary {ticker= (str)}';
    public const DESCRIPTION = 'Position summary';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $portfolio = $commander->portfolio();

        $ticker = $this->arg('ticker')->getValue();

        foreach ($portfolio as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $this->output->writeLn($position->summary());
        }

        if (!$ticker) {
            $this->output->writeLn($portfolio->summary());
        }

        return self::SUCCESS;
    }
}
