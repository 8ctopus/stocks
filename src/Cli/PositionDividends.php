<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use DateTime;
use Oct8pus\Stocks\Helper;
use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class PositionDividends extends Command
{
    public const NAME = 'dividends {--summary=false (bool)} {--year=-1 (int)} {--ticker= (str)}';
    public const DESCRIPTION = 'Position dividends';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();
        $summary = $this->arg('summary')->getValue();
        $year = $this->arg('year')->getValue();
        $year = $year > 0 ? $year : null;

        $totalDividends = 0;
        $totalAcquistionCost = 0;
        $currentValue = 0;

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $dividends = $position->dividends($year);
            $totalDividends += $dividends;
            $acquistionCost = $position->acquisitionCostOn(new DateTime('2024-04-17'));
            $totalAcquistionCost += $acquistionCost;
            $currentValue += $position->currentValue();

            if ($summary) {
                $data[] = [
                    $position->ticker(),
                    (int) $position->dividends($year),
                    //(int) $acquistionCost,
                    Helper::sprintf('(%+.1f%%)', 100 * $dividends / $acquistionCost),
                ];
            } else {
                $this->output->writeLn($position->reportDividends($year));
            }
        }

        if ($summary) {
            $data[] = ['-'];
        }

        $data[] = ['TOTAL DIVIDEND INCOME' . (!$year ? '' : " {$year}"), (int) $totalDividends];
        $data[] = ['ACQUISTION COST', (int) $totalAcquistionCost, Helper::sprintf('(%+.1f%%)', 100 * $totalDividends / $totalAcquistionCost)];
        $data[] = ['CURRENT VALUE', (int) $currentValue, Helper::sprintf('(%+.1f%%)', 100 * $totalDividends / $currentValue)];

        $this->output->writeLn((string) new Table($data));

        return self::SUCCESS;
    }
}
