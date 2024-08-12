<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Oct8pus\Stocks\Table;
use Swew\Cli\Command;

class PositionsList extends Command
{
    const NAME = 'positions:list';
    const DESCRIPTION = 'List positions';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        foreach ($commander->portfolio() as $position) {
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