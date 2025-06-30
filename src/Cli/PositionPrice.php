<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use DivisionByZeroError;
use Oct8pus\Stocks\Helper;
use Swew\Cli\Command;

class PositionPrice extends Command
{
    public const NAME = 'update-price {--ticker= (str)}';
    public const DESCRIPTION = 'Update position price';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $filter = $this->arg('ticker')->getValue();

        $total = 0;

        foreach ($portfolio as $position) {
            if ($filter && $filter !== $position->ticker()) {
                continue;
            }

            $ticker = $position->ticker();
            $previous = $position->price();

            $price = $this->output->ask("\nupdate price for {$ticker} {$previous}: ");

            if (!empty($price)) {
                $price = (float) $price;

                $profit = $position->sharePriceProfit();

                $position->setPrice($price);

                $profit = $position->sharePriceProfit() - $profit;
                $total += $profit;

                try {
                    $change = Helper::sprintf('price change %+.1f%% ', 100 * ($price - $previous) / $previous);
                } catch (DivisionByZeroError) {
                    $change = Helper::sprintf('price change +∞ ', 0);
                }

                $this->output->info($change . number_format($profit, 0, '.', '\''));
            }
        }

        $portfolio->save();

        $this->output->info(Helper::sprintf('change %+d', $total));

        return self::SUCCESS;
    }
}
