<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use DateTime;
use Oct8pus\Stocks\Transaction;
use Swew\Cli\Command;

class PositionAddTransaction extends Command
{
    public const NAME = 'add {ticker (str)} {date (str)} {shares (int)} {price (float)}';
    public const DESCRIPTION = 'Add position transaction';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $ticker = $this->arg('ticker')->getValue();
        $date = DateTime::createFromFormat('Y-m-d', $this->arg('date')->getValue());
        $price = $this->arg('price')->getValue();
        $shares = $this->arg('shares')->getValue();

        $transaction = new Transaction($date, $shares, $price);

        foreach ($portfolio as $position) {
            if ($position->ticker() === $ticker) {
                $transactions = $position->transactions();

                $transactions->add($transaction);
            }
        }

        $portfolio->save();

        return self::SUCCESS;
    }
}
