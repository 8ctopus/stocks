<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use DateTime;
use Oct8pus\Stocks\Cash;
use Oct8pus\Stocks\Dividend;
use Oct8pus\Stocks\DividendHistory;
use Oct8pus\Stocks\Position;
use Oct8pus\Stocks\Transaction;
use Oct8pus\Stocks\Transactions;
use Swew\Cli\Command;

class CashImport extends Command
{
    public const NAME = 'import-cash {price (float)}';
    public const DESCRIPTION = 'Import cash';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $price = $this->arg('price')->getValue();

        foreach ($portfolio as $position) {
            if ($position->ticker() === 'cash') {
                break;
            }

            $position = null;
        }

        if (!$position) {
            $position = new Cash($price);
        }

        $position->setPrice($price);

        $portfolio
            ->add($position)
            ->save();

        return self::SUCCESS;
    }
}
