<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class UpdatePrice extends Command
{
    const NAME = 'stocks:price {ticker (str)} {price (float)}';
    const DESCRIPTION = 'Update stock price';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $ticker = $this->arg('ticker')->getValue();
        $price = $this->arg('price')->getValue();

        foreach ($portfolio as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $position->setPrice($price);
        }

        $portfolio->save();
        return self::SUCCESS;
    }
}