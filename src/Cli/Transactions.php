<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class Transactions extends Command
{
    const NAME = 'transactions {ticker= (str)}';
    const DESCRIPTION = 'Transactions for position';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $this->output->writeLn($position->report('transactions'));
        }

        return self::SUCCESS;
    }
}