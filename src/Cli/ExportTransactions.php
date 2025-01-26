<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use Swew\Cli\Command;

class ExportTransactions extends Command
{
    public const NAME = 'export {--summary=false (bool)} {ticker= (str)}';
    public const DESCRIPTION = 'Export transactions';

    public function __invoke() : int
    {
        $commander = $this->getCommander();

        $ticker = $this->arg('ticker')->getValue();

        $this->output->writeLn('Symbol/ISIN;Quantity;Cost Per Share;Purchase Date');

        foreach ($commander->portfolio() as $position) {
            if ($ticker && $ticker !== $position->ticker()) {
                continue;
            }

            $currentTicker = $position->ticker();

            if ($this->arg('summary')->getValue()) {
                $shares = $position->shares();
                $price = $position->acquisitionCost() / $shares;
                $date = $position->transactions()[0]->date()->format('Y/m/d');

                $this->output->writeLn("{$currentTicker};{$shares};{$price};{$date}");
            } else {
                foreach ($position->transactions() as $transaction) {
                    $shares = $transaction->shares();
                    $price = $transaction->price();
                    $date = $transaction->date()->format('Y/m/d');

                    $this->output->writeLn("{$currentTicker};{$shares};{$price};{$date}");
                }
            }
        }

        return self::SUCCESS;
    }
}
