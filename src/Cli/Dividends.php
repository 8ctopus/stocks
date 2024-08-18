<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class Dividends extends Command
{
    const NAME = 'dividends {--summary (bool)} {ticker= (str)}';
    const DESCRIPTION = 'Dividends for position';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();
        $summary = $this->arg('summary')->getValue();

        $dividends = 0;
        $acquisitionCost = 0;
        $currentValue = 0;

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $dividends += $position->dividends();
            $acquisitionCost += $position->acquisitionCost();
            $currentValue += $position->currentValue();

            if (!$summary) {
                $this->output->writeLn($position->report('dividends'));
            }
        }

        if (!$ticker) {
            $data = [
                ['TOTAL DIVIDENDS', $dividends],
                ['ACQUISTION COST', $acquisitionCost, sprintf('(%+.1f%%)', 100 * $dividends / $acquisitionCost)],
                ['CURRENT VALUE', $currentValue, sprintf('(%+.1f%%)', 100 * $dividends / $currentValue)],
            ];

            $this->output->writeLn((string) new Table($data, ''));
        }

        return self::SUCCESS;
    }
}