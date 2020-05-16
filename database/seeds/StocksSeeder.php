<?php

use App\Stock;

use Illuminate\Database\Seeder;

class StocksSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('Seed stocks...');

        // delete all stocks
        $stocks = Stock::all();

        foreach ($stocks as $stock)
            $stock->delete();

        $stocks = [
            [
                'ticker'      => 'ROG:SW',
                'name'        => 'Roche Holding AG',
                'currency'    => 'CHF',
            ],
            [
                'ticker'      => 'NESN:SW',
                'name'        => 'Nestle SA',
                'currency'    => 'CHF',
            ],
            [
                'ticker'      => 'NOVN:SW',
                'name'        => 'Novartis AG',
                'currency'    => 'CHF',
            ],
            [
                'ticker'      => 'ZURN:SW',
                'name'        => 'Zurich Assurances Group AG',
                'currency'    => 'CHF',
            ],
            [
                'ticker'      => 'SREN:SW',
                'name'        => 'Swiss Re AG',
                'currency'    => 'CHF',
            ],
            [
                'ticker'      => 'BALN:SW',
                'name'        => 'Baloise Holding AG',
                'currency'    => 'CHF',
            ],
            [
                'ticker'      => 'UNA:NA',
                'name'        => 'Unilever NV',
                'currency'    => 'EUR',
            ],
            [
                'ticker'      => 'RDSA:NA',
                'name'        => 'Royal Dutch Shell PLC',
                'currency'    => 'EUR',
            ],
            [
                'ticker'      => 'KO:US',
                'name'        => 'Coca-Cola Co/The',
                'currency'    => 'USD',
            ],
        ];

        foreach ($stocks as $stock)
            Stock::create($stock);
    }
}
