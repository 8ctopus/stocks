<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(StocksSeeder::class);

        if (file_exists('MyTradesSeeder.php'))
            $this->call(MyTradesSeeder::class);
        else
            $this->call(TradesSeeder::class);

        $this->call(StocksPriceHistory::class);
    }
}
