<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class PositionsStats extends Command
{
    const NAME = 'positions:stats {ticker= (str)}';
    const DESCRIPTION = 'Positions stats';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $this->output->writeLn($position->ticker());
            $this->output->writeLn($position->report('profit'));
        }

        return self::SUCCESS;
    }
}