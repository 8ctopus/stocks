<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class PositionPrice extends Command
{
    public const NAME = 'price {--ticker= (str)}';
    public const DESCRIPTION = 'Update position price';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $filter = $this->arg('ticker')->getValue();

        foreach ($portfolio as $position) {
            if ($filter && $filter !== $position->ticker()) {
                continue;
            }

            $ticker = $position->ticker();
            $price = $position->price();

            $price = $this->output->ask("update price for {$ticker} {$price}: ");
            $position->setPrice((float) $price);
        }

        $portfolio->save();
        return self::SUCCESS;
    }
}
