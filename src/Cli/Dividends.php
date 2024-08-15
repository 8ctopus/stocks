<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class Dividends extends Command
{
    const NAME = 'dividends {ticker= (str)}';
    const DESCRIPTION = 'Dividends for position';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $this->output->writeLn($position->ticker() . "\n" . $position->report('dividends'));
        }

        return self::SUCCESS;
    }
}