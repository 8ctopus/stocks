<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class PositionDividends extends Command
{
    const NAME = 'position:dividends {--summary=false (bool)} {--year= (int)}';
    const DESCRIPTION = 'Position dividends';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $summary = $this->arg('summary')->getValue();
        $year = $this->arg('year')->getValue();

        $dividends = 0;
        $acquisitionCost = 0;
        $currentValue = 0;

        foreach ($commander->portfolio() as $position) {
            $dividends += $position->dividends($year);
            $acquisitionCost += $position->acquisitionCost();
            $currentValue += $position->currentValue();

            if (!$summary) {
                $this->output->writeLn($position->reportDividends($year));
            }
        }

        $data = [
            ["TOTAL DIVIDENDS" . (!$year ? '' : " {$year}"), $dividends],
            ['ACQUISTION COST', (int) $acquisitionCost, sprintf('(%+.1f%%)', 100 * $dividends / $acquisitionCost)],
            ['CURRENT VALUE', (int) $currentValue, sprintf('(%+.1f%%)', 100 * $dividends / $currentValue)],
        ];

        $this->output->writeLn((string) new Table($data, ''));

        return self::SUCCESS;
    }
}