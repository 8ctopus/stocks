<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class PositionList extends Command
{
    public const NAME = 'list {ticker= (str)}';
    public const DESCRIPTION = 'List positions';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        /** @disregard P1013 */
        $portfolio = $commander->portfolio();

        $ticker = $this->arg('ticker')->getValue();

        foreach ($portfolio as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            if ($position->shares() === 0) {
                continue;
            }

            $data[] = [
                $position->ticker(),
                $position->shares(),
            ];
        }

        $table = (string) new Table($data, 'POSITIONS');

        $this->output->writeLn($table);

        return self::SUCCESS;
    }
}
