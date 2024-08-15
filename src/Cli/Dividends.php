<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class Dividends extends Command
{
    const NAME = 'dividends {ticker= (str)}';
    const DESCRIPTION = 'Dividends for position';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();

        $total = 0;

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $total += $position->dividends();
            $this->output->writeLn($position->report('dividends'));
        }

        if (!$ticker) {
            $data = [
                ['TOTAL DIVIDENDS'],
                ['-'],
                [$total],
            ];


            $this->output->writeLn((string) new Table($data, ''));
        }

        return self::SUCCESS;
    }
}