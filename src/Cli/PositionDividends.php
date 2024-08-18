<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class PositionDividends extends Command
{
    const NAME = 'position:dividends {ticker= (str)} {--summary=false (bool)} {--year=-1 (int)}';
    const DESCRIPTION = 'Position dividends';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();
        $summary = $this->arg('summary')->getValue();
        $year = $this->arg('year')->getValue();
        $year = $year > 0 ? $year : null;

        $dividends = 0;
        $acquisitionCost = 0;
        //$currentValue = 0;

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $dividends += $position->dividends($year);
            $acquisitionCost += $position->acquisitionCost();
            //$currentValue += $position->currentValue();

            if (!$summary) {
                $this->output->writeLn($position->reportDividends($year));
            }
        }

        $data = [
            ["TOTAL DIVIDENDS" . (!$year ? '' : " {$year}"), (int) $dividends],
            ['ACQUISTION COST', (int) $acquisitionCost, sprintf('(%+.1f%%)', 100 * $dividends / $acquisitionCost)],
            //['CURRENT VALUE', (int) $currentValue, sprintf('(%+.1f%%)', 100 * $dividends / $currentValue)],
        ];

        $this->output->writeLn((string) new Table($data, ''));

        return self::SUCCESS;
    }
}
