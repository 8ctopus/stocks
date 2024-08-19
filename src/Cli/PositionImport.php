<?php

declare(strict_types=1);

namespace Oct8pus\Stocks\Cli;

use DateTime;
use Oct8pus\Stocks\Dividend;
use Oct8pus\Stocks\DividendHistory;
use Oct8pus\Stocks\Position;
use Oct8pus\Stocks\Transaction;
use Oct8pus\Stocks\Transactions;
use Swew\Cli\Command;

class PositionImport extends Command
{
    const NAME = 'import {ticker (str)} {price (float)}';
    const DESCRIPTION = 'Import position';

    public function __invoke() : int
    {
        $commander = $this->getCommander();
        $portfolio = $commander->portfolio();

        $ticker = $this->arg('ticker')->getValue();

        $import = '';

        while (1) {
            $input = $this->output->ask('Enter transactions');

            if (empty($input)) {
                break;
            }

            $import .= trim($input) . "\n";
        }

        $list = explode("\n", trim($import));

        $transactions = new Transactions();

        foreach ($list as $item) {
            $params = explode(" ", str_replace("\t", " ", $item));
            $date = DateTime::createFromFormat('d.m.Y', $params[0]);
            file_put_contents(__DIR__ .'/../../test.txt', $params[1]);

            $units = (int) str_replace(chr(0), '', $params[1]);
            $price = (float) $params[2];

            $transactions->add(new Transaction($date, $units, $price));
        }

        $import = '';

        while (1) {
            $input = $this->output->ask('Enter dividend history');

            if (empty($input)) {
                break;
            }

            $import .= trim($input) . "\n";
        }

        $list = explode("\n", trim($import));

        $history = new DividendHistory();

        foreach ($list as $item) {
            $params = explode(" ", str_replace("\t", " ", $item));
            $date = DateTime::createFromFormat('d.m.Y', $params[0]);
            $units = (int) $params[1];
            $price = (float) $params[2];

            $history->add(new Dividend($date, $price));
        }

        $position = new Position($ticker, $this->arg('price')->getValue(), $transactions);
        $position->setDividendHistory($history);

        $portfolio->add($position);
        $portfolio->save();

        return self::SUCCESS;
    }
}
