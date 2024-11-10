<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class PositionCleanup extends Command
{
    public const NAME = 'cleanup';
    public const DESCRIPTION = 'Cleanup position';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $portfolio = $commander->portfolio();

        foreach ($portfolio as $position) {
            $position->cleanup();
        }

        $portfolio->save();

        return self::SUCCESS;
    }
}
