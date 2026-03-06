<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class PortfolioSave extends Command
{
    public const NAME = 'save';
    public const DESCRIPTION = 'Save portfolio';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        /** @disregard P1013 */
        $commander->portfolio()->save();

        return self::SUCCESS;
    }
}
