<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Helper;
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
            $previous = $position->price();

            $price = (float) $this->output->ask("update price for {$ticker} {$previous}: ");
            $position->setPrice($price);

            $this->output->info(Helper::sprintf('price change %+.1f%%', 100 *($price - $previous) / $previous));
        }

        $portfolio->save();
        return self::SUCCESS;
    }
}
