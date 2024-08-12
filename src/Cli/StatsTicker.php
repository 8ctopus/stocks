<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class StatsTicker extends Command
{
    const NAME = 'stats {ticker}';
    const DESCRIPTION = 'List stocks';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $this->output->writeLn($position->report('transactions') . $position->report('profit') . $position->report('dividends'));
        }

        return self::SUCCESS;
    }
}