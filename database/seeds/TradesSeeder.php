<?php

use App\User;
use App\Trade;
use App\Stock;

use Illuminate\Database\Seeder;

class TradesSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('Seed trades...');

        // delete all trades
        $trades = Trade::all();

        foreach ($trades as $trade)
            $trade->delete();

        $trades = [
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'ROG:SW')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2019-09-27',
                'purchase_price' => 288.50,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'NESN:SW')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2019-10-17',
                'purchase_price' => 104.79,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'NESN:SW')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2019-12-13',
                'purchase_price' => 102.65,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'NOVN:SW')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2019-09-27',
                'purchase_price' => 86.42,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'ZURN:SW')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2018-07-12',
                'purchase_price' => 310.29,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'SREN:SW')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2019-09-27',
                'purchase_price' => 104.07,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'UNA:NA')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2019-10-17',
                'purchase_price' => 54.77,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'RDSA:NA')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2018-12-04',
                'purchase_price' => 26.69,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'BALN:SW')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2020-04-24',
                'purchase_price' => 140.91,
            ],
            [
                'user_id'        => User::where('name', 'user1')->first()->id,
                'stock_id'       => Stock::where('ticker', 'KO:US')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2020-04-24',
                'purchase_price' => 45.48,
            ],
            [
                'user_id'        => User::where('name', 'user2')->first()->id,
                'stock_id'       => Stock::where('ticker', 'KO:US')->first()->id,
                'quantity'       => 100,
                'purchase_date'  => '2020-04-24',
                'purchase_price' => 45.48,
            ],
        ];

        foreach ($trades as $trade)
            Trade::create($trade);
    }
}
