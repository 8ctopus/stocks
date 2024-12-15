<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

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

        $currentValue = $portfolio->currentValue();

        foreach ($portfolio as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $this->output->writeLn($position->summary($currentValue));
        }

        if (!$ticker) {
            $this->output->writeLn($portfolio->summary());
        }

        $portfolio->save();

        return self::SUCCESS;
    }
}
