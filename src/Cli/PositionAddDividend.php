<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use DateTime;
use Oct8pus\Stocks\Dividend;
use Oct8pus\Stocks\DividendHistory;
use Swew\Cli\Command;

class PositionAddDividend extends Command
{
    public const NAME = 'add dividend {ticker (str)} {date (str)} {dividend (float)}';
    public const DESCRIPTION = 'Add position dividend';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $ticker = $this->arg('ticker')->getValue();
        $date = DateTime::createFromFormat('Y-m-d', $this->arg('date')->getValue());
        $dividend = $this->arg('dividend')->getValue();

        $dividend = new Dividend($date, $dividend);

        foreach ($portfolio as $position) {
            if ($position->ticker() === $ticker) {
                $history = $position->dividendHistory();

                if (!$history) {
                    $history = new DividendHistory();
                }

                $history->add($dividend);
            }
        }

        //$portfolio->save();

        return self::SUCCESS;
    }
}
