<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
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

        $spacer = $ticker ? [] : [['']];

        $data = [];

        foreach ($portfolio as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            if ($position->shares() === 0) {
                continue;
            }

            $data = array_merge($data, $position->summary($currentValue), $spacer);
        }

        if (!$ticker) {
            $data = array_merge($data, $portfolio->summary());
        }

        $this->output->writeLn((string) new Table($data));

        $portfolio->save();

        return self::SUCCESS;
    }
}
