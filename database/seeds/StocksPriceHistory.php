<?php

use App\Stock;
use App\StockPriceHistory;
use Illuminate\Database\Seeder;

class StocksPriceHistory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('Seed stock price history...');

        // delete all trades
        $stocks = StockPriceHistory::all();

        foreach ($stocks as $stock)
            $stock->delete();

        $stock = [
            [
                'stock_id'  => Stock::where('ticker', 'NESN:SW')->first()->id,
                'timestamp' => strtotime('2020-04-24 23:59:00'),
                'price'     => 103.86,
            ],
        ];

        foreach ($stocks as $stock)
            StockPriceHistory::create($stock);
    }
}
