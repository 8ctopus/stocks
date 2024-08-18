<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class PositionPrice extends Command
{
    const NAME = 'position:price {--ticker= (str)}';
    const DESCRIPTION = 'Update position price';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $ticker = $this->arg('ticker')->getValue();

        foreach ($portfolio as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $currentTicker = $position->ticker();

            $price = (float) $this->output->ask("insert price for {$currentTicker}: ");
            $position->setPrice($price);
        }

        $portfolio->save();
        return self::SUCCESS;
    }
}